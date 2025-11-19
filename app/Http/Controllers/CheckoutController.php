<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Pemesanan;
use App\Models\DetailPemesanan;
use App\Models\Pembayaran;
use App\Models\Pengiriman;
use App\Services\XenditService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


class CheckoutController extends Controller
{
    protected $xenditService;

    public function __construct()
    {
        $this->xenditService = new XenditService();
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

        $subtotal = Cart::getTotalPrice($customerId);
        $totalItems = Cart::getTotalItems($customerId);

        // Get customer data
        $customer = Auth::guard('customer')->user();

        // Shipping cost estimation (simplified version, you can integrate with real shipping API)
        $ongkir = 15000; // Fixed shipping cost for demo
        $grandTotal = $subtotal + $ongkir;

        return view('frontend.checkout', compact(
            'cartItems',
            'subtotal',
            'totalItems',
            'customer',
            'ongkir',
            'grandTotal'
        ));
    }

    /**
     * Process checkout and create order with Xendit payment
     */
    /**
 * Process checkout and create order with Xendit payment
 */
public function process(Request $request)
{
    $request->validate([
        'nama_penerima' => 'required|string|max:255',
        'no_hp_penerima' => 'required|string|max:20',
        'alamat_tujuan' => 'required|string',
        'metode_pembayaran' => 'required|in:va,ewallet,qris,retail,cod',
        'channel' => 'nullable|string', // Ubah menjadi nullable
        'ekspedisi' => 'nullable|string',
        'layanan' => 'nullable|string',
        'biaya_ongkir' => 'required|numeric',
    ]);

    $customerId = Auth::guard('customer')->id();
    $customer = Auth::guard('customer')->user();

    // Get cart items
    $cartItems = Cart::where('id_customer', $customerId)
        ->with(['detailUkuran.produk'])
        ->get();

    if ($cartItems->isEmpty()) {
        return response()->json([
            'success' => false,
            'message' => 'Keranjang Anda kosong'
        ], 400);
    }

    try {
        DB::beginTransaction();

        // Calculate totals
        $subtotal = $cartItems->sum(function ($item) {
            return $item->subtotal;
        });

        $ongkir = $request->biaya_ongkir;
        $grandTotal = $subtotal + $ongkir;

        // Create pemesanan
        $pemesanan = Pemesanan::create([
            'id_customer' => $customerId,
            'tanggal_pemesanan' => now(),
            'total_harga' => $grandTotal,
            'status' => Pemesanan::STATUS_PENDING,
        ]);

        // Create detail pemesanan from cart items
        foreach ($cartItems as $cartItem) {
            if (!$cartItem->detailUkuran->stokMencukupi($cartItem->jumlah)) {
                throw new \Exception("Stok untuk {$cartItem->detailUkuran->produk->nama_produk} tidak mencukupi");
            }

            DetailPemesanan::create([
                'id_pemesanan' => $pemesanan->id_pemesanan,
                'id_ukuran' => $cartItem->id_ukuran,
                'jumlah' => $cartItem->jumlah,
                'subtotal' => $cartItem->subtotal,
            ]);
        }

        // Generate external_id untuk pembayaran
        $externalId = 'ORDER-' . $pemesanan->id_pemesanan . '-' . Str::random(6);

        // Handle channel untuk COD
        $channel = $request->channel;
        if ($request->metode_pembayaran === 'cod') {
            $channel = 'COD';
        }

        // Create pembayaran record
        $pembayaranData = [
            'id_pemesanan' => $pemesanan->id_pemesanan,
            'metode_pembayaran' => $request->metode_pembayaran,
            'channel' => $channel,
            'jumlah_bayar' => $grandTotal,
            'status_pembayaran' => $request->metode_pembayaran === 'cod' 
                ? Pembayaran::STATUS_BELUM_BAYAR 
                : Pembayaran::STATUS_MENUNGGU,
            'external_id' => $externalId,
        ];

        $pembayaran = Pembayaran::create($pembayaranData);

        // Create pengiriman
        $pengiriman = Pengiriman::create([
            'id_pemesanan' => $pemesanan->id_pemesanan,
            'nama_penerima' => $request->nama_penerima,
            'no_hp_penerima' => $request->no_hp_penerima,
            'alamat_tujuan' => $request->alamat_tujuan,
            'ekspedisi' => $request->ekspedisi ?? 'JNE',
            'layanan' => $request->layanan ?? 'REG',
            'biaya_ongkir' => $ongkir,
            'status_pengiriman' => Pengiriman::STATUS_MENUNGGU,
        ]);

        // Handle payment creation based on method (kecuali COD)
        $paymentResult = [];
        if ($request->metode_pembayaran !== 'cod') {
            // Validasi channel untuk non-COD
            if (empty($request->channel)) {
                throw new \Exception('Channel pembayaran harus dipilih');
            }

            $paymentResult = $this->createXenditPayment($pembayaran, $pemesanan, $customer, $request);

            if (!$paymentResult['success']) {
                throw new \Exception($paymentResult['error']);
            }

            // Update pembayaran dengan Xendit data
            $pembayaran->update($paymentResult['pembayaran_data']);
        }

        // Clear cart
        Cart::clearCart($customerId);

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Pesanan berhasil dibuat',
            'order_id' => $pemesanan->id_pemesanan,
            'payment_url' => $paymentResult['payment_url'] ?? route('order.success', $pemesanan->id_pemesanan),
            'redirect_url' => route('order.success', $pemesanan->id_pemesanan)
        ]);

    } catch (\Exception $e) {
        DB::rollBack();

        return response()->json([
            'success' => false,
            'message' => 'Gagal membuat pesanan: ' . $e->getMessage()
        ], 500);
    }
}

    /**
     * Create Xendit payment based on method
     */
    private function createXenditPayment($pembayaran, $pemesanan, $customer, $request)
{
    $externalId = $pembayaran->external_id;
    $amount = (int) $pembayaran->jumlah_bayar;
    $expirationDate = now()->addDays(1)->toISOString();

    $baseData = [
        'external_id' => $externalId,
        'amount' => $amount,
        'name' => $customer->nama,
        'order_id' => $pemesanan->id_pemesanan,
        'expiration_date' => $expirationDate,
        'success_redirect_url' => route('payment.success', $pemesanan->id_pemesanan),
        'failure_redirect_url' => route('payment.failed', $pemesanan->id_pemesanan),
    ];

    $pembayaranData = [
        'xendit_external_id' => $externalId,
        'xendit_expiry_date' => $expirationDate,
    ];

    switch ($request->metode_pembayaran) {
        case 'va':
            $result = $this->xenditService->createVirtualAccount(array_merge($baseData, [
                'bank_code' => $request->channel,
            ]));
            
            if ($result['success']) {
                $responseData = $result['data'];
                $pembayaranData['xendit_id'] = $responseData['id'] ?? null;
                $pembayaranData['xendit_payment_url'] = $responseData['payment_url'] ?? null;
                $pembayaranData['xendit_merchant_name'] = $responseData['merchant_name'] ?? null;
                $pembayaranData['xendit_account_number'] = $responseData['account_number'] ?? null;
            }
            break;

        case 'ewallet':
            $result = $this->xenditService->createEWalletPayment(array_merge($baseData, [
                'channel_code' => $request->channel,
                'phone_number' => $customer->no_hp,
            ]));
            
            if ($result['success']) {
                $responseData = $result['data'];
                $pembayaranData['xendit_id'] = $responseData['id'] ?? null;
                $pembayaranData['xendit_payment_url'] = $responseData['checkout_url'] ?? 
                    $responseData['actions']['desktop_web_checkout_url'] ?? null;
            }
            break;

        case 'qris':
            $result = $this->xenditService->createQRISPayment($baseData);
            
            if ($result['success']) {
                $responseData = $result['data'];
                $pembayaranData['xendit_id'] = $responseData['id'] ?? null;
                $pembayaranData['xendit_payment_url'] = $responseData['qr_string'] ?? null;
            }
            break;

        case 'retail':
            $result = $this->xenditService->createRetailPayment(array_merge($baseData, [
                'retail_outlet_name' => $request->channel,
            ]));
            
            if ($result['success']) {
                $responseData = $result['data'];
                $pembayaranData['xendit_id'] = $responseData['id'] ?? null;
                $pembayaranData['xendit_payment_url'] = $responseData['payment_url'] ?? null;
            }
            break;

        default:
            throw new \Exception('Metode pembayaran tidak didukung');
    }

    if (!$result['success']) {
        throw new \Exception($result['error']);
    }

    return [
        'success' => true,
        'payment_url' => $pembayaranData['xendit_payment_url'] ?? route('order.success', $pemesanan->id_pemesanan),
        'pembayaran_data' => $pembayaranData
    ];
}

    /**
     * Handle Xendit webhook for payment status updates
     */
    public function handleWebhook(Request $request)
    {
        \Log::info('Xendit Webhook Received:', $request->all());

        try {
            $event = $request->event;
            $data = $request->data;

            // Verify webhook (you might want to add signature verification)
            if ($event === 'payment.captured' || $event === 'payment.succeeded') {
                $this->handleSuccessfulPayment($data);
            } elseif ($event === 'payment.failed' || $event === 'payment.expired') {
                $this->handleFailedPayment($data);
            }

            return response()->json(['status' => 'success']);

        } catch (\Exception $e) {
            \Log::error('Webhook Error:', ['error' => $e->getMessage()]);
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 400);
        }
    }

    /**
     * Handle successful payment
     */
    private function handleSuccessfulPayment($data)
    {
        DB::transaction(function () use ($data) {
            $externalId = $data['external_id'] ?? $data['reference_id'];
            
            // Find pembayaran by external_id
            $pembayaran = Pembayaran::where('external_id', $externalId)->first();
            
            if ($pembayaran) {
                $pembayaran->update([
                    'status_pembayaran' => Pembayaran::STATUS_SUDAH_BAYAR,
                    'xendit_id' => $data['id'] ?? $pembayaran->xendit_id,
                ]);

                // Update pemesanan status
                $pemesanan = $pembayaran->pemesanan;
                if ($pemesanan) {
                    $pemesanan->update(['status' => Pemesanan::STATUS_DIPROSES]);
                }

                \Log::info('Payment marked as paid:', ['external_id' => $externalId]);
            }
        });
    }

    /**
     * Handle failed payment
     */
    private function handleFailedPayment($data)
    {
        DB::transaction(function () use ($data) {
            $externalId = $data['external_id'] ?? $data['reference_id'];
            
            $pembayaran = Pembayaran::where('external_id', $externalId)->first();
            
            if ($pembayaran) {
                $pembayaran->update([
                    'status_pembayaran' => Pembayaran::STATUS_GAGAL,
                ]);

                \Log::info('Payment marked as failed:', ['external_id' => $externalId]);
            }
        });
    }

    /**
     * Check payment status manually
     */
    public function checkPaymentStatus($orderId)
    {
        try {
            $pembayaran = Pembayaran::where('id_pemesanan', $orderId)->first();
            
            if (!$pembayaran || !$pembayaran->xendit_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pembayaran tidak ditemukan'
                ]);
            }

            $result = $this->xenditService->getPaymentStatus(
                $pembayaran->xendit_id, 
                $pembayaran->metode_pembayaran
            );

            if ($result['success']) {
                // Update status based on Xendit response
                $status = $result['data']['status'] ?? null;
                
                if (in_array($status, ['COMPLETED', 'SUCCEEDED'])) {
                    $pembayaran->update(['status_pembayaran' => Pembayaran::STATUS_SUDAH_BAYAR]);
                    $pembayaran->pemesanan->update(['status' => Pemesanan::STATUS_DIPROSES]);
                } elseif (in_array($status, ['FAILED', 'EXPIRED'])) {
                    $pembayaran->update(['status_pembayaran' => Pembayaran::STATUS_GAGAL]);
                }

                return response()->json([
                    'success' => true,
                    'status' => $pembayaran->status_pembayaran,
                    'xendit_status' => $status
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => $result['error']
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Calculate shipping cost (you can integrate with real shipping API)
     */
    public function calculateShipping(Request $request)
    {
        $request->validate([
            'ekspedisi' => 'required|string',
            'layanan' => 'required|string',
            'destination' => 'nullable|string',
        ]);

        // Simplified shipping cost calculation
        // In real application, integrate with RajaOngkir or other shipping API
        $shippingCosts = [
            'JNE' => ['REG' => 15000, 'YES' => 25000, 'OKE' => 10000],
            'TIKI' => ['REG' => 14000, 'ONS' => 24000, 'ECO' => 9000],
            'POS' => ['REG' => 13000, 'NEXT' => 23000],
            'SiCepat' => ['REG' => 12000, 'BEST' => 20000],
            'JNT' => ['REG' => 11000, 'EXPRESS' => 19000],
        ];

        $ekspedisi = strtoupper($request->ekspedisi);
        $layanan = strtoupper($request->layanan);

        $cost = $shippingCosts[$ekspedisi][$layanan] ?? 15000;

        return response()->json([
            'success' => true,
            'cost' => $cost,
            'cost_formatted' => 'Rp ' . number_format($cost, 0, ',', '.'),
            'estimation' => '2-3 hari'
        ]);
    }

    /**
     * Payment success page
     */
    public function paymentSuccess($orderId)
    {
        $pemesanan = Pemesanan::with(['pembayaran', 'customer'])->find($orderId);
        
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