<?php

namespace App\Http\Controllers;

use App\Models\Pemesanan;
use App\Models\DetailPemesanan;
use App\Models\Customer;
use App\Models\DetailUkuran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PesananController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            Log::debug('Memulai proses mengambil data pesanan');
            
            $pesanan = Pemesanan::with(['customer', 'detailPemesanan'])
                ->latest('created_at', 'desc')
                ->orderBy('created_at', 'desc')
                ->paginate(10);

            Log::debug('Berhasil mengambil data pesanan', [
                'jumlah' => $pesanan->count(),
                'total' => $pesanan->total()
            ]);
            
            return view('admin.pesanan.index', compact('pesanan'));
            
        } catch (\Exception $e) {
            Log::error('Error pada index pesanan: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()->with('error', 'Terjadi kesalahan saat mengambil data pesanan');
        }
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $customers = Customer::all();
        $detailUkuran = DetailUkuran::with('produk')->get();
        
        return view('admin.pesanan.create', compact('customers', 'detailUkuran'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_customer' => 'required|exists:customer,id_customer',
            'tanggal_pemesanan' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.id_ukuran' => 'required|exists:detail_ukuran,id_ukuran',
            'items.*.jumlah' => 'required|integer|min:1',
        ]);

        DB::beginTransaction();

        try {
            // Create pemesanan
            $pemesanan = Pemesanan::create([
                'id_customer' => $request->id_customer,
                'tanggal_pemesanan' => $request->tanggal_pemesanan,
                'total_harga' => 0, // Will be calculated from details
                'status' => Pemesanan::STATUS_PENDING,
            ]);

            // Create detail pemesanan
            $totalHarga = 0;
            foreach ($request->items as $item) {
                $detailUkuran = DetailUkuran::find($item['id_ukuran']);
                $subtotal = $detailUkuran->harga * $item['jumlah'];

                DetailPemesanan::create([
                    'id_pemesanan' => $pemesanan->id_pemesanan,
                    'id_ukuran' => $item['id_ukuran'],
                    'jumlah' => $item['jumlah'],
                    'subtotal' => $subtotal,
                ]);

                $totalHarga += $subtotal;
            }

            // Update total harga pemesanan
            $pemesanan->update(['total_harga' => $totalHarga]);

            DB::commit();

            return redirect()->route('pesanan.index')
                ->with('success', 'Pesanan berhasil dibuat.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $pesanan = Pemesanan::with([
            'customer', 
            'detailPemesanan.detailUkuran.produk',
            'pembayaran',
            'pengiriman'
        ])->findOrFail($id);

        return view('admin.pesanan.show', compact('pesanan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $pesanan = Pemesanan::with(['detailPemesanan.detailUkuran'])->findOrFail($id);
        $customers = Customer::all();
        $detailUkuran = DetailUkuran::with('produk')->get();

        return view('admin.pesanan.edit', compact('pesanan', 'customers', 'detailUkuran'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'id_customer' => 'required|exists:customer,id_customer',
            'tanggal_pemesanan' => 'required|date',
            'status' => 'required|in:pending,diproses,dikirim,selesai,batal',
            'items' => 'required|array|min:1',
            'items.*.id_ukuran' => 'required|exists:detail_ukuran,id_ukuran',
            'items.*.jumlah' => 'required|integer|min:1',
        ]);

        DB::beginTransaction();

        try {
            $pemesanan = Pemesanan::findOrFail($id);

            // Update pemesanan
            $pemesanan->update([
                'id_customer' => $request->id_customer,
                'tanggal_pemesanan' => $request->tanggal_pemesanan,
                'status' => $request->status,
            ]);

            // Delete existing detail pemesanan
            $pemesanan->detailPemesanan()->delete();

            // Create new detail pemesanan
            $totalHarga = 0;
            foreach ($request->items as $item) {
                $detailUkuran = DetailUkuran::find($item['id_ukuran']);
                $subtotal = $detailUkuran->harga * $item['jumlah'];

                DetailPemesanan::create([
                    'id_pemesanan' => $pemesanan->id_pemesanan,
                    'id_ukuran' => $item['id_ukuran'],
                    'jumlah' => $item['jumlah'],
                    'subtotal' => $subtotal,
                ]);

                $totalHarga += $subtotal;
            }

            // Update total harga pemesanan
            $pemesanan->update(['total_harga' => $totalHarga]);

            DB::commit();

            return redirect()->route('pesanan.index')
                ->with('success', 'Pesanan berhasil diperbarui.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        DB::beginTransaction();

        try {
            $pemesanan = Pemesanan::findOrFail($id);
            
            // Delete detail pemesanan first (will trigger stock restoration)
            $pemesanan->detailPemesanan()->delete();
            
            // Then delete pemesanan
            $pemesanan->delete();

            DB::commit();

            return redirect()->route('pesanan.index')
                ->with('success', 'Pesanan berhasil dihapus.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Update status pesanan - tambahkan method khusus ini
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,diproses,dikirim,selesai,batal'
        ]);

        $pesanan = Pemesanan::findOrFail($id);
        $oldStatus = $pesanan->status;
        $pesanan->update(['status' => $request->status]);

        return redirect()->back()
            ->with('success', 'Status pesanan berhasil diubah dari ' . $oldStatus . ' menjadi ' . $request->status);
    }

    /**
     * Filter orders
     */
    public function filter(Request $request)
    {
        try {
            $status = $request->input('status', '');
            $sortBy = $request->input('sort_by', 'terbaru');
            $minTotal = $request->input('min_total', '');
            $maxTotal = $request->input('max_total', '');
            $minItems = $request->input('min_items', '');
            $maxItems = $request->input('max_items', '');
            
            Log::debug('Memulai proses filter pesanan', [
                'status' => $status,
                'sort_by' => $sortBy,
                'min_total' => $minTotal,
                'max_total' => $maxTotal,
                'min_items' => $minItems,
                'max_items' => $maxItems
            ]);

            // Base query dengan eager loading
            $query = Pemesanan::with(['customer', 'detailPemesanan']);

            // Filter by status
            if (!empty($status) && $status !== 'semua') {
                $query->where('status', $status);
            }

            // Filter by total range
            if (!empty($minTotal)) {
                $query->where('total_harga', '>=', $minTotal);
            }
            
            if (!empty($maxTotal)) {
                $query->where('total_harga', '<=', $maxTotal);
            }

            // Filter by items count
            if (!empty($minItems) || !empty($maxItems)) {
                $query->whereHas('detailPemesanan', function($q) use ($minItems, $maxItems) {
                    if (!empty($minItems)) {
                        $q->havingRaw('COUNT(*) >= ?', [$minItems]);
                    }
                    if (!empty($maxItems)) {
                        $q->havingRaw('COUNT(*) <= ?', [$maxItems]);
                    }
                });
            }

            // Sorting
            switch ($sortBy) {
                case 'terlama':
                    $query->orderBy('created_at', 'asc');
                    break;
                case 'total_tertinggi':
                    $query->orderBy('total_harga', 'desc');
                    break;
                case 'total_terendah':
                    $query->orderBy('total_harga', 'asc');
                    break;
                case 'items_terbanyak':
                    $query->withCount('detailPemesanan')->orderBy('detail_pemesanan_count', 'desc');
                    break;
                case 'items_tersedikit':
                    $query->withCount('detailPemesanan')->orderBy('detail_pemesanan_count', 'asc');
                    break;
                case 'tanggal_pemesanan_asc':
                    $query->orderBy('tanggal_pemesanan', 'asc');
                    break;
                case 'tanggal_pemesanan_desc':
                    $query->orderBy('tanggal_pemesanan', 'desc');
                    break;
                case 'terbaru':
                default:
                    $query->orderBy('created_at', 'desc');
                    break;
            }

            $pesanan = $query->paginate(10);

            Log::debug('Filter pesanan berhasil', [
                'jumlah_ditemukan' => $pesanan->total(),
                'status' => $status
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'html' => view('admin.pesanan.partials.pesanan-table', compact('pesanan'))->render(),
                    'pagination' => (string) $pesanan->links(),
                    'total' => $pesanan->total()
                ]);
            }

            return view('admin.pesanan.index', compact('pesanan', 'status', 'sortBy', 'minTotal', 'maxTotal', 'minItems', 'maxItems'));

        } catch (\Exception $e) {
            Log::error('Error pada filter pesanan: ' . $e->getMessage(), [
                'status' => $status ?? '',
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat memfilter pesanan'
                ], 500);
            }

            return redirect()->route('admin.pesanan.index')
                ->with('error', 'Terjadi kesalahan saat memfilter pesanan: ' . $e->getMessage());
        }
    }
}