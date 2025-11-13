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
                'message' => 'Stock not available'
            ], 400);
        }

        return response()->json([
            'success' => true,
            'message' => 'Cart updated successfully',
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

    /**
     * Get cart count for AJAX requests
     */
    public function getCount()
    {
        $customerId = Auth::guard('customer')->id();
        
        return response()->json([
            'count' => Cart::getTotalItems($customerId)
        ]);
    }
}