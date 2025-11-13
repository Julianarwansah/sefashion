<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Statistik utama
        $totalProducts = DB::table('produk')->count();
        $totalCustomers = DB::table('customers')->count();
        $totalOrders = DB::table('pemesanan')->count();
        
        // Total pendapatan (hanya pemesanan dengan status selesai)
        $totalRevenue = DB::table('pemesanan')
            ->where('status', 'selesai')
            ->sum('total_harga');

        // Data pemesanan terbaru
        $recentOrders = DB::table('pemesanan as p')
            ->join('customers as c', 'p.id_customer', '=', 'c.id_customer')
            ->select('p.*', 'c.nama as customer_name')
            ->orderBy('p.tanggal_pemesanan', 'desc')
            ->limit(10)
            ->get();

        // Status pemesanan
        $orderStatus = DB::table('pemesanan')
            ->select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->get();

        // Produk terlaris
        $bestSellingProducts = DB::table('detail_pemesanan as dp')
            ->join('detail_ukuran as du', 'dp.id_ukuran', '=', 'du.id_ukuran')
            ->join('produk as p', 'du.id_produk', '=', 'p.id_produk')
            ->select(
                'p.nama_produk',
                DB::raw('SUM(dp.jumlah) as total_terjual'),
                DB::raw('SUM(dp.subtotal) as total_pendapatan')
            )
            ->groupBy('p.id_produk', 'p.nama_produk')
            ->orderBy('total_terjual', 'desc')
            ->limit(5)
            ->get();

        // Data untuk chart
        $monthlyOrders = DB::table('pemesanan')
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

        // Stok produk rendah
        $lowStockProducts = DB::table('produk')
            ->where('total_stok', '<=', 10)
            ->orderBy('total_stok', 'asc')
            ->limit(5)
            ->get();

        // Pembayaran pending
        $pendingPayments = DB::table('pembayaran as pay')
            ->join('pemesanan as p', 'pay.id_pemesanan', '=', 'p.id_pemesanan')
            ->join('customers as c', 'p.id_customer', '=', 'c.id_customer')
            ->where('pay.status_pembayaran', 'menunggu')
            ->select('pay.*', 'p.total_harga', 'c.nama as customer_name')
            ->orderBy('pay.created_at', 'desc')
            ->limit(5)
            ->get();

        return view('admin.pages.dashboard', compact(
            'totalProducts',
            'totalCustomers',
            'totalOrders',
            'totalRevenue',
            'recentOrders',
            'orderStatus',
            'bestSellingProducts',
            'monthlyOrders',
            'lowStockProducts',
            'pendingPayments'
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