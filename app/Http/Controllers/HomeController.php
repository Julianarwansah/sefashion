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
        $products = Produk::with(['detailUkuran', 'detailWarna', 'gambarProduk'])
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
        $query = Produk::with(['detailUkuran', 'detailWarna', 'gambarProduk'])
            ->where('total_stok', '>', 0);

        // Filter by category if provided
        if ($request->has('category') && $request->category) {
            $query->where('kategori', $request->category);
        }

        // Search
        if ($request->has('search') && $request->search) {
            $query->where('nama_produk', 'like', '%' . $request->search . '%');
        }

        // Sort
        if ($request->has('sort')) {
            switch ($request->sort) {
                case 'price_low':
                    $query->join('detail_ukuran', 'produk.id_produk', '=', 'detail_ukuran.id_produk')
                        ->select('produk.*')
                        ->groupBy('produk.id_produk')
                        ->orderByRaw('MIN(detail_ukuran.harga) ASC');
                    break;
                case 'price_high':
                    $query->join('detail_ukuran', 'produk.id_produk', '=', 'detail_ukuran.id_produk')
                        ->select('produk.*')
                        ->groupBy('produk.id_produk')
                        ->orderByRaw('MAX(detail_ukuran.harga) DESC');
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
    public function show($id)
    {
        $product = Produk::with(['detailUkuran', 'detailWarna', 'gambarProduk'])
            ->findOrFail($id);

        // Related products (same category)
        $relatedProducts = Produk::with(['detailUkuran', 'detailWarna', 'gambarProduk'])
            ->where('kategori', $product->kategori)
            ->where('id_produk', '!=', $product->id_produk)
            ->where('total_stok', '>', 0)
            ->take(4)
            ->get();

        return view('frontend.product-detail', compact('product', 'relatedProducts'));
    }
}