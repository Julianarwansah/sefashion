<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Display home page
     */
    public function index()
    {
        // Ambil produk terbaru atau featured products
        $products = Produk::with('gambar')
            ->where('total_stok', '>', 0)
            ->latest()
            ->take(8)
            ->get();

        return view('frontend.home', compact('products'));
    }

    /**
     * Display shop page with all products
     */
    public function shop(Request $request)
    {
        $query = Produk::with('gambar')->where('total_stok', '>', 0);

        // Filter by category if provided
        if ($request->has('category')) {
            $query->where('kategori', $request->category);
        }

        // Search
        if ($request->has('search')) {
            $query->where('nama_produk', 'like', '%' . $request->search . '%');
        }

        // Sort
        if ($request->has('sort')) {
            switch ($request->sort) {
                case 'price_low':
                    $query->orderBy('harga', 'asc');
                    break;
                case 'price_high':
                    $query->orderBy('harga', 'desc');
                    break;
                case 'newest':
                    $query->latest();
                    break;
                default:
                    $query->latest();
            }
        } else {
            $query->latest();
        }

        $products = $query->paginate(12);

        return view('frontend.shop', compact('products'));
    }

    /**
     * Display product detail page
     */
    public function productDetail($id)
    {
        $product = Produk::with('gambar')->findOrFail($id);
        
        // Related products (same category)
        $relatedProducts = Produk::with('gambar')
            ->where('kategori', $product->kategori)
            ->where('id', '!=', $product->id)
            ->where('total_stok', '>', 0)
            ->take(4)
            ->get();

        return view('frontend.product-detail', compact('product', 'relatedProducts'));
    }
}