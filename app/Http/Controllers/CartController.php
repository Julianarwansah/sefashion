<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\DetailUkuran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * Display cart page
     */
    public function index()
    {
        $customerId = Auth::guard('customer')->id();

        $cartItems = Cart::where('id_customer', $customerId)
            ->with(['detailUkuran.detailWarna', 'detailUkuran.produk'])
            ->orderBy('tanggal_ditambahkan', 'desc')
            ->get();

        $totalPrice = Cart::getTotalPrice($customerId);
        $totalItems = Cart::getTotalItems($customerId);

        return view('frontend.cart', compact('cartItems', 'totalPrice', 'totalItems'));
    }

    /**
     * Add item to cart
     */
    public function add(Request $request)
    {
        $request->validate([
            'id_ukuran' => 'required|exists:detail_ukuran,id_ukuran',
            'jumlah' => 'required|integer|min:1',
        ]);

        $customerId = Auth::guard('customer')->id();

        $result = Cart::addToCart($customerId, $request->id_ukuran, $request->jumlah);

        if (!$result) {
            return response()->json([
                'success' => false,
                'message' => 'Stok tidak mencukupi'
            ], 400);
        }

        return response()->json([
            'success' => true,
            'message' => 'Produk berhasil ditambahkan ke keranjang',
            'cart_count' => Cart::getTotalItems($customerId)
        ]);
    }

    /**
     * Update cart item quantity
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'jumlah' => 'required|integer|min:1',
        ]);

        $customerId = Auth::guard('customer')->id();

        $cartItem = Cart::where('id_cart', $id)
            ->where('id_customer', $customerId)
            ->firstOrFail();

        $result = $cartItem->updateQuantity($request->jumlah);

        if (!$result) {
            return response()->json([
                'success' => false,
                'message' => 'Stok tidak mencukupi'
            ], 400);
        }

        return response()->json([
            'success' => true,
            'message' => 'Keranjang berhasil diupdate',
            'subtotal' => $cartItem->fresh()->subtotal,
            'subtotal_formatted' => $cartItem->fresh()->subtotal_formatted,
            'total' => Cart::getTotalPrice($customerId),
            'total_formatted' => Cart::getTotalPriceFormatted($customerId)
        ]);
    }

    /**
     * Remove item from cart
     */
    public function remove($id)
    {
        $customerId = Auth::guard('customer')->id();

        $cartItem = Cart::where('id_cart', $id)
            ->where('id_customer', $customerId)
            ->firstOrFail();

        $cartItem->delete();

        return response()->json([
            'success' => true,
            'message' => 'Produk berhasil dihapus dari keranjang',
            'cart_count' => Cart::getTotalItems($customerId),
            'total' => Cart::getTotalPrice($customerId),
            'total_formatted' => Cart::getTotalPriceFormatted($customerId)
        ]);
    }

    /**
     * Clear all cart items
     */
    public function clear()
    {
        $customerId = Auth::guard('customer')->id();

        Cart::clearCart($customerId);

        return redirect()->route('cart.index')->with('success', 'Keranjang berhasil dikosongkan');
    }
}
