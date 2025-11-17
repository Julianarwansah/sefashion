<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\DetailUkuran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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

        $totalPrice = 0;
        $totalItems = 0;

        foreach ($cartItems as $item) {
            $totalPrice += $item->subtotal;
            $totalItems += $item->jumlah;
        }

        return view('frontend.cart', compact('cartItems', 'totalPrice', 'totalItems'));
    }

    /**
     * Add item to cart
     */
    public function add(Request $request)
    {
        // Check authentication first
        if (!Auth::guard('customer')->check()) {
            return response()->json([
                'success' => false,
                'message' => 'Please login first',
                'redirect' => route('login') // Ganti dengan route login yang benar
            ], 401);
        }

        $request->validate([
            'id_ukuran' => 'required|exists:detail_ukuran,id_ukuran',
            'jumlah' => 'required|integer|min:1',
        ]);

        $customerId = Auth::guard('customer')->id();

        // Check if the size exists and has enough stock
        $detailUkuran = DetailUkuran::find($request->id_ukuran);
        
        if (!$detailUkuran) {
            return response()->json([
                'success' => false,
                'message' => 'Product size not found'
            ], 404);
        }

        if ($detailUkuran->stok < $request->jumlah) {
            return response()->json([
                'success' => false,
                'message' => 'Stock not available. Only ' . $detailUkuran->stok . ' items left'
            ], 400);
        }

        // Check if item already exists in cart
        $existingCart = Cart::where('id_customer', $customerId)
                            ->where('id_ukuran', $request->id_ukuran)
                            ->first();

        if ($existingCart) {
            // Update quantity if item exists
            $newQuantity = $existingCart->jumlah + $request->jumlah;
            
            if ($detailUkuran->stok < $newQuantity) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot add more items. You already have ' . $existingCart->jumlah . ' in cart. Only ' . $detailUkuran->stok . ' items left in stock'
                ], 400);
            }

            $existingCart->update(['jumlah' => $newQuantity]);
            $cartItem = $existingCart;
        } else {
            // Create new cart item
            $cartItem = Cart::create([
                'id_customer' => $customerId,
                'id_ukuran' => $request->id_ukuran,
                'jumlah' => $request->jumlah,
                'tanggal_ditambahkan' => now(),
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Product successfully added to cart',
            'cart_count' => Cart::getTotalItems($customerId),
            'cart_item' => [
                'id' => $cartItem->id_cart,
                'quantity' => $cartItem->jumlah,
                'product_name' => $detailUkuran->produk->nama_produk ?? 'Unknown Product',
                'size' => $detailUkuran->ukuran,
                'color' => $detailUkuran->detailWarna->nama_warna ?? 'Unknown Color'
            ]
        ]);
    }

    /**
     * Update cart item quantity
     */
    public function update(Request $request, $id)
    {
        // Check authentication first
        if (!Auth::guard('customer')->check()) {
            return response()->json([
                'success' => false,
                'message' => 'Session expired. Please login again.',
                'redirect' => route('login') // Ganti dengan route login yang benar
            ], 401);
        }

        $request->validate([
            'jumlah' => 'required|integer|min:1',
        ]);

        $customerId = Auth::guard('customer')->id();

        $cartItem = Cart::where('id_cart', $id)
            ->where('id_customer', $customerId)
            ->first();

        if (!$cartItem) {
            return response()->json([
                'success' => false,
                'message' => 'Item keranjang tidak ditemukan'
            ], 404);
        }

        // Check stock
        if ($cartItem->detailUkuran && $cartItem->detailUkuran->stok < $request->jumlah) {
            return response()->json([
                'success' => false,
                'message' => 'Stock not available'
            ], 400);
        }

        // Update using query builder to avoid timestamp issues
        $updated = DB::table('cart')
            ->where('id_cart', $id)
            ->where('id_customer', $customerId)
            ->update(['jumlah' => $request->jumlah]);

        if ($updated) {
            // Reload the item to get fresh data
            $cartItem = Cart::find($id);
            $cartItem->load('detailUkuran');

            return response()->json([
                'success' => true,
                'message' => 'Keranjang berhasil diupdate',
                'subtotal' => $cartItem->subtotal,
                'subtotal_formatted' => $cartItem->subtotal_formatted,
                'total' => Cart::getTotalPrice($customerId),
                'total_formatted' => Cart::getTotalPriceFormatted($customerId),
                'cart_count' => Cart::getTotalItems($customerId),
                'available_stock' => $cartItem->detailUkuran->stok ?? 0
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Gagal mengupdate keranjang'
        ], 400);
    }

    /**
     * Remove item from cart
     */
    public function remove($id)
    {
        // Check authentication first
        if (!Auth::guard('customer')->check()) {
            return response()->json([
                'success' => false,
                'message' => 'Session expired. Please login again.',
                'redirect' => route('login') // Ganti dengan route login yang benar
            ], 401);
        }

        $customerId = Auth::guard('customer')->id();

        $cartItem = Cart::where('id_cart', $id)
            ->where('id_customer', $customerId)
            ->first();

        if (!$cartItem) {
            return response()->json([
                'success' => false,
                'message' => 'Item keranjang tidak ditemukan'
            ], 404);
        }

        $cartItem->delete();

        return response()->json([
            'success' => true,
            'message' => 'Product removed from cart successfully',
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

        return redirect()->route('cart.index')->with('success', 'Cart cleared successfully');
    }
}