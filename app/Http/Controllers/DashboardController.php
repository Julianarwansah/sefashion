<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Produk;
use App\Models\Customer;
use App\Models\Pemesanan;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Total Statistics
        $totalProduk = Produk::count();
        $totalCustomer = Customer::count();
        $totalPemesanan = Pemesanan::count();
        $totalPendapatan = Pemesanan::where('status', 'selesai')->sum('total_harga');
        
        // Pemesanan Status
        $pemesananPending = Pemesanan::where('status', 'pending')->count();
        $pemesananSelesai = Pemesanan::where('status', 'selesai')->count();
        
        // Monthly Statistics
        $startOfMonth = now()->startOfMonth();
        $endOfMonth = now()->endOfMonth();
        
        $produkBaruBulanIni = Produk::whereBetween('created_at', [$startOfMonth, $endOfMonth])->count();
        $customerBaruBulanIni = Customer::whereBetween('created_at', [$startOfMonth, $endOfMonth])->count();
        
        // Low Stock Products
        $stokRendah = Produk::where('total_stok', '<', 10)->count();
        $lowStockProducts = Produk::where('total_stok', '<', 10)
            ->orderBy('total_stok', 'asc')
            ->limit(5)
            ->get();
        
        // Recent Data
        $recentProducts = Produk::with(['detailUkuran' => function($query) {
            $query->orderBy('harga', 'asc');
        }])
        ->orderBy('created_at', 'desc')
        ->limit(5)
        ->get();
        
        $recentOrders = Pemesanan::with('customer')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        // Chart Data - Products by Category
        $chartData = Produk::select('kategori', DB::raw('count(*) as total'))
            ->groupBy('kategori')
            ->get();
        
        $chartLabels = $chartData->pluck('kategori')->toArray();
        $chartValues = $chartData->pluck('total')->toArray();
        
        return view('admin.pages.dashboard', array_merge(
    compact(
        'totalProduk',
        'totalCustomer',
        'totalPemesanan',
        'totalPendapatan',
        'pemesananPending',
        'pemesananSelesai',
        'produkBaruBulanIni',
        'customerBaruBulanIni',
        'stokRendah',
        'lowStockProducts',
        'recentProducts',
        'recentOrders',
    ),
    [
        'chartData' => [
            'labels' => $chartLabels,
            'data' => $chartValues
        ]
    ]
));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Tidak digunakan untuk dashboard
        abort(404);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Tidak digunakan untuk dashboard
        abort(404);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Tidak digunakan untuk dashboard
        abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // Tidak digunakan untuk dashboard
        abort(404);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Tidak digunakan untuk dashboard
        abort(404);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Tidak digunakan untuk dashboard
        abort(404);
    }

    /**
     * Method tambahan untuk AJAX (bisa diakses via route terpisah)
     */
    public function getChartData(Request $request)
    {
        $period = $request->get('period', 'monthly');
        
        switch ($period) {
            case 'weekly':
                $data = DB::table('pemesanan')
                    ->select(
                        DB::raw('YEAR(tanggal_pemesanan) as year'),
                        DB::raw('WEEK(tanggal_pemesanan) as week'),
                        DB::raw('COUNT(*) as total_orders'),
                        DB::raw('SUM(total_harga) as total_revenue')
                    )
                    ->where('status', 'selesai')
                    ->where('tanggal_pemesanan', '>=', now()->subWeeks(12))
                    ->groupBy('year', 'week')
                    ->orderBy('year', 'asc')
                    ->orderBy('week', 'asc')
                    ->get();
                break;

            case 'daily':
                $data = DB::table('pemesanan')
                    ->select(
                        DB::raw('DATE(tanggal_pemesanan) as date'),
                        DB::raw('COUNT(*) as total_orders'),
                        DB::raw('SUM(total_harga) as total_revenue')
                    )
                    ->where('status', 'selesai')
                    ->where('tanggal_pemesanan', '>=', now()->subDays(30))
                    ->groupBy('date')
                    ->orderBy('date', 'asc')
                    ->get();
                break;

            default:
                $data = DB::table('pemesanan')
                    ->select(
                        DB::raw('YEAR(tanggal_pemesanan) as year'),
                        DB::raw('MONTH(tanggal_pemesanan) as month'),
                        DB::raw('COUNT(*) as total_orders'),
                        DB::raw('SUM(total_harga) as total_revenue')
                    )
                    ->where('status', 'selesai')
                    ->where('tanggal_pemesanan', '>=', now()->subMonths(12))
                    ->groupBy('year', 'month')
                    ->orderBy('year', 'asc')
                    ->orderBy('month', 'asc')
                    ->get();
        }

        return response()->json($data);
    }

    public function getRealTimeStats()
    {
        $todayOrders = DB::table('pemesanan')
            ->whereDate('tanggal_pemesanan', today())
            ->count();

        $todayRevenue = DB::table('pemesanan')
            ->where('status', 'selesai')
            ->whereDate('tanggal_pemesanan', today())
            ->sum('total_harga');

        $pendingOrders = DB::table('pemesanan')
            ->where('status', 'pending')
            ->count();

        $pendingPaymentsCount = DB::table('pembayaran')
            ->where('status_pembayaran', 'menunggu')
            ->count();

        return response()->json([
            'today_orders' => $todayOrders,
            'today_revenue' => $todayRevenue,
            'pending_orders' => $pendingOrders,
            'pending_payments' => $pendingPaymentsCount
        ]);
    }
}