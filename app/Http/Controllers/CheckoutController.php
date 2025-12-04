<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Pemesanan;
use App\Models\DetailPemesanan;
use App\Models\Pembayaran;
use App\Models\Pengiriman;
use App\Services\XenditService;
use App\Services\BinderbyteService;
use App\Services\ManualShippingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;


class CheckoutController extends Controller
{
    protected $xenditService;
    protected $binderbyteService;
    protected $manualShippingService;

    public function __construct(
        BinderbyteService $binderbyteService,
        ManualShippingService $manualShippingService
    ) {
        $this->xenditService = new XenditService();
        $this->binderbyteService = $binderbyteService;
        $this->manualShippingService = $manualShippingService;
    }

    /**
     * Display checkout page
     */
    public function index()
    {
        $customerId = Auth::guard('customer')->id();

        // Get cart items
        $cartItems = Cart::where('id_customer', $customerId)
            ->with(['detailUkuran.detailWarna', 'detailUkuran.produk'])
            ->get();

        // Check if cart is empty
        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', 'Keranjang Anda kosong');
        }

        // Check stock availability
        foreach ($cartItems as $item) {
            if (!$item->isStockAvailable()) {
                return redirect()->route('cart.index')
                    ->with('error', "Stok untuk {$item->detailUkuran->produk->nama_produk} tidak mencukupi");
            }
        }

        // Calculate total weight (gram) from cart items
        $totalWeight = 0;
        foreach ($cartItems as $item) {
            // Assuming detailUkuran has a 'berat' field (in gram)
            $totalWeight += ($item->detailUkuran->berat ?? 0) * $item->jumlah;
        }

        // Pass weight to view
        $weight = $totalWeight;

        $subtotal = Cart::getTotalPrice($customerId);
        $totalItems = Cart::getTotalItems($customerId);

        // Get customer data
        $customer = Auth::guard('customer')->user();

        // Check if customer profile is complete
        if (!$customer->hasCompleteProfile()) {
            return redirect()->route('profile')
                ->with('error', 'Please complete your profile (Name, Email, Phone, Address, Province, City) before checkout.');
        }

        // Shipping cost estimation (simplified version, you can integrate with real shipping API)
        $ongkir = 0; // Default 0 until calculated
        $grandTotal = $subtotal + $ongkir;

        // Get Provinces for dropdown (BinderByte)
        $provinces = $this->binderbyteService->getProvinces();

        // Debug logging
        Log::info('Checkout Index - Provinces loaded', [
            'count' => count($provinces),
            'sample' => count($provinces) > 0 ? $provinces[0] : null
        ]);

        // If provinces is empty, log error
        if (empty($provinces)) {
            Log::error('Checkout: No provinces loaded from BinderByte API');
        }

        return view('frontend.checkout', compact(
            'cartItems',
            'subtotal',
            'totalItems',
            'customer',
            'ongkir',
            'grandTotal',
            'provinces',
            'weight'
        ));
    }

    /**
     * Process checkout and create order
     */
    public function process(Request $request)
    {
        try {
            $customerId = Auth::guard('customer')->id();

            // Validate request
            $validated = $request->validate([
                'nama_penerima' => 'required|string',
                'no_hp_penerima' => 'required|string',
                'alamat_tujuan' => 'required|string',
                'province_id' => 'required|string',
                'destination' => 'required|string',
                'ekspedisi' => 'required|string',
                'layanan' => 'required|string',
                'ongkir' => 'required|numeric',
                'metode_pembayaran' => 'required|string',
                'channel' => 'nullable|string',
            ]);

            // Get cart items
            $cartItems = Cart::where('id_customer', $customerId)->get();
            if ($cartItems->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Keranjang kosong'
                ], 400);
            }

            // Calculate totals
            $subtotal = Cart::getTotalPrice($customerId);
            $ongkir = $validated['ongkir'];
            $grandTotal = $subtotal + $ongkir;

            DB::beginTransaction();

            // Create order
            $pemesanan = Pemesanan::create([
                'id_customer' => $customerId,
                'tanggal_pemesanan' => now(),
                'status' => 'pending',
                'total_harga' => $grandTotal,
            ]);

            // Create order details
            foreach ($cartItems as $item) {
                DetailPemesanan::create([
                    'id_pemesanan' => $pemesanan->id_pemesanan,
                    'id_ukuran' => $item->id_ukuran,
                    'jumlah' => $item->jumlah,
                    'subtotal' => $item->detailUkuran->harga * $item->jumlah,
                ]);

                // Update stock
                $item->detailUkuran->decrement('stok', $item->jumlah);
            }

            // Create shipping record
            Pengiriman::create([
                'id_pemesanan' => $pemesanan->id_pemesanan,
                'nama_penerima' => $validated['nama_penerima'],
                'no_hp_penerima' => $validated['no_hp_penerima'],
                'alamat_tujuan' => $validated['alamat_tujuan'],
                'ekspedisi' => $validated['ekspedisi'],
                'layanan' => $validated['layanan'],
                'biaya_ongkir' => $ongkir,
                'status_pengiriman' => Pengiriman::STATUS_MENUNGGU,
            ]);

            // Create payment record
            $pembayaran = Pembayaran::create([
                'id_pemesanan' => $pemesanan->id_pemesanan,
                'metode_pembayaran' => $validated['metode_pembayaran'],
                'jumlah_bayar' => $grandTotal,
                'status_pembayaran' => Pembayaran::STATUS_MENUNGGU,
                'channel' => $validated['channel'] ?? null,
                'tanggal_pembayaran' => null,
            ]);

            // Handle payment based on method
            $paymentUrl = null;
            if ($validated['metode_pembayaran'] === 'cod') {
                // COD - no payment URL needed
                $pembayaran->update(['status_pembayaran' => Pembayaran::STATUS_MENUNGGU]);
            } else {
                // Create Xendit payment
                $channel = $validated['channel'] ?? null;
                $xenditResponse = $this->xenditService->createPayment(
                    $pemesanan->id_pemesanan,
                    $grandTotal,
                    $validated['metode_pembayaran'],
                    $channel
                );

                if ($xenditResponse['success']) {
                    $xenditData = $xenditResponse['data'] ?? [];
                    $pembayaran->update([
                        'external_id' => $xenditResponse['external_id'] ?? null,
                        'xendit_id' => $xenditData['id'] ?? null,
                        'xendit_external_id' => $xenditData['external_id'] ?? null,
                        'xendit_account_number' => $xenditData['account_number'] ?? null,
                        'xendit_merchant_name' => $xenditData['merchant_code'] ?? $xenditData['name'] ?? null,
                        'xendit_expiry_date' => isset($xenditData['expiration_date']) ? \Carbon\Carbon::parse($xenditData['expiration_date']) : null,
                    ]);
                    $paymentUrl = $xenditResponse['payment_url'] ?? null;
                } else {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => 'Gagal membuat pembayaran: ' . ($xenditResponse['message'] ?? 'Unknown error')
                    ], 500);
                }
            }

            // Clear cart
            Cart::where('id_customer', $customerId)->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pesanan berhasil dibuat',
                'order_id' => $pemesanan->id_pemesanan,
                'payment_url' => $paymentUrl,
                'redirect_url' => route('order.success', $pemesanan->id_pemesanan)
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Checkout Process Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal memproses checkout: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Calculate shipping cost using ManualShippingService
     */
    public function calculateShipping(Request $request)
    {
        $request->validate([
            'ekspedisi' => 'required|string',
            'destination' => 'required|string', // City ID
            'weight' => 'nullable|integer', // Weight in grams
        ]);

        try {
            // Use BinderByte origin city ID (Jakarta Pusat = 3171, but ManualService expects ID mapped in DB)
            // In Seeder we mapped 152 (Jakarta Selatan) to Java Zone.
            // Let's use 152 as default origin for now to match seeder data.
            $origin = config('services.rajaongkir.origin_city_id', '152');

            $destination = $request->destination;
            $weight = $request->weight ?? 1000; // Default 1kg if not specified
            $courier = strtolower($request->ekspedisi);

            Log::info('Calculate Shipping Request (Manual)', [
                'origin' => $origin,
                'destination' => $destination,
                'weight' => $weight,
                'courier' => $courier
            ]);

            $costs = $this->manualShippingService->calculateShipping($origin, $destination, $weight, $courier);

            $formattedCosts = [];

            foreach ($costs as $c) {
                $costValue = $c['cost'] ?? 0;
                $etdValue = $c['etd'] ?? '-';
                $serviceName = $c['service'] ?? 'Unknown Service';
                $description = $c['description'] ?? $serviceName;

                $formattedCosts[] = [
                    'service' => $serviceName,
                    'description' => $description,
                    'cost' => $costValue,
                    'cost_formatted' => 'Rp ' . number_format($costValue, 0, ',', '.'),
                    'etd' => $etdValue
                ];
            }

            Log::info('Shipping costs calculated successfully', [
                'count' => count($formattedCosts)
            ]);

            return response()->json([
                'success' => true,
                'costs' => $formattedCosts
            ]);

        } catch (\Exception $e) {
            Log::error('Calculate Shipping Error', [
                'error' => $e->getMessage(),
                'request' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal menghitung ongkir: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get Cities by Province ID (BinderByte)
     */
    public function getCities(Request $request)
    {
        $provinceId = $request->province_id;
        try {
            if ($provinceId) {
                $cities = $this->binderbyteService->getCities($provinceId);
            } else {
                $cities = [];
            }

            return response()->json([
                'success' => true,
                'cities' => $cities
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat kota'
            ], 500);
        }
    }

    /**
     * Payment success page
     */
    public function paymentSuccess($orderId)
    {
        $pemesanan = Pemesanan::with([
            'pembayaran',
            'customer',
            'detailPemesanan.detailUkuran.produk.gambarProduk',
            'detailPemesanan.detailUkuran.detailWarna'
        ])->find($orderId);

        if (!$pemesanan) {
            return redirect()->route('home')->with('error', 'Pesanan tidak ditemukan');
        }

        return view('frontend.payment-success', compact('pemesanan'));
    }

    /**
     * Payment failed page
     */
    public function paymentFailed($orderId)
    {
        $pemesanan = Pemesanan::with(['pembayaran'])->find($orderId);

        if (!$pemesanan) {
            return redirect()->route('home')->with('error', 'Pesanan tidak ditemukan');
        }

        return view('frontend.payment-failed', compact('pemesanan'));
    }
}