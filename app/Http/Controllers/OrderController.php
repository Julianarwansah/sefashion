<?php

namespace App\Http\Controllers;

use App\Models\Pemesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * Display a listing of customer's orders.
     */
    public function index()
    {
        $customer = Auth::guard('customer')->user();

        $orders = Pemesanan::with([
            'detailPemesanan.detailUkuran.produk.gambarProduk',
            'detailPemesanan.detailUkuran.detailWarna',
            'pembayaran',
            'pengiriman'
        ])
        ->where('id_customer', $customer->id_customer)
        ->latest('tanggal_pemesanan')
        ->paginate(10);

        return view('frontend.my-orders', compact('orders'));
    }

    /**
     * Display the specified order.
     */
    public function show($id)
    {
        $customer = Auth::guard('customer')->user();

        $order = Pemesanan::with([
            'customer',
            'detailPemesanan.detailUkuran.produk.gambarProduk',
            'detailPemesanan.detailUkuran.detailWarna',
            'pembayaran',
            'pengiriman'
        ])
        ->where('id_customer', $customer->id_customer)
        ->where('id_pemesanan', $id)
        ->firstOrFail();

        return view('frontend.order-detail', compact('order'));
    }

    /**
     * Cancel an order (if status is still pending)
     */
    public function cancel($id)
    {
        $customer = Auth::guard('customer')->user();

        $order = Pemesanan::where('id_customer', $customer->id_customer)
            ->where('id_pemesanan', $id)
            ->firstOrFail();

        // Only allow cancellation if order is pending
        if ($order->status !== 'pending') {
            return redirect()->back()->with('error', 'Order can only be cancelled when status is pending.');
        }

        $order->update(['status' => 'batal']);

        return redirect()->route('my-orders')->with('success', 'Order has been cancelled successfully.');
    }
}
