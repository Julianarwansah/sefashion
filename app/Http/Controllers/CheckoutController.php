<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Pemesanan;
use App\Models\DetailPemesanan;
use App\Models\Pembayaran;
use App\Models\Pengiriman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
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
     * Process checkout and create order
     */
    public function process(Request $request)
    {
        $request->validate([
            'nama_penerima' => 'required|string|max:255',
            'no_hp_penerima' => 'required|string|max:20',
            'alamat_tujuan' => 'required|string',
            'metode_pembayaran' => 'required|in:transfer,cod,ewallet,va,qris',
            'ekspedisi' => 'nullable|string',
            'layanan' => 'nullable|string',
        ]);

        $customerId = Auth::guard('customer')->id();

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

            $ongkir = $request->biaya_ongkir ?? 15000;
            $grandTotal = $subtotal + $ongkir;

            // Create pemesanan
            $pemesanan = Pemesanan::create([
                'id_customer' => $customerId,
                'tanggal_pemesanan' => now(),
                'total_harga' => $subtotal,
                'status' => Pemesanan::STATUS_PENDING,
            ]);

            // Create detail pemesanan from cart items
            foreach ($cartItems as $cartItem) {
                // Check stock one more time
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

            // Create pembayaran
            $pembayaran = Pembayaran::create([
                'id_pemesanan' => $pemesanan->id_pemesanan,
                'metode_pembayaran' => $request->metode_pembayaran,
                'channel' => $request->channel,
                'jumlah_bayar' => $grandTotal,
                'status_pembayaran' => $request->metode_pembayaran === 'cod'
                    ? Pembayaran::STATUS_BELUM_BAYAR
                    : Pembayaran::STATUS_MENUNGGU,
            ]);

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

            // Clear cart
            Cart::clearCart($customerId);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pesanan berhasil dibuat',
                'order_id' => $pemesanan->id_pemesanan,
                'payment_url' => $pembayaran->payment_url,
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
}
