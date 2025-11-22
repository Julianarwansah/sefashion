<?php

namespace App\Http\Controllers;

use App\Models\Pengiriman;
use App\Models\Pemesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PengirimanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            Log::debug('Memulai proses mengambil data pengiriman');
            
            $pengiriman = Pengiriman::with(['pemesanan'])
                ->latest('created_at', 'desc')
                ->orderBy('created_at', 'desc')
                ->paginate(10);

            Log::debug('Berhasil mengambil data pengiriman', [
                'jumlah' => $pengiriman->count(),
                'total' => $pengiriman->total()
            ]);
            
            return view('admin.pengiriman.index', compact('pengiriman'));
            
        } catch (\Exception $e) {
            Log::error('Error pada index pengiriman: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()->with('error', 'Terjadi kesalahan saat mengambil data pengiriman');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pemesanan = Pemesanan::whereDoesntHave('pengiriman')
            ->orWhereHas('pengiriman', function($query) {
                $query->whereIn('status_pengiriman', [Pengiriman::STATUS_GAGAL]);
            })
            ->whereIn('status', [Pemesanan::STATUS_DIPROSES, Pemesanan::STATUS_DIKIRIM])
            ->with('customer')
            ->get();

        $ekspedisiList = [
            'JNE' => 'JNE',
            'TIKI' => 'TIKI',
            'POS Indonesia' => 'POS Indonesia',
            'J&T' => 'J&T',
            'Sicepat' => 'Sicepat',
            'Anteraja' => 'Anteraja',
            'Ninja Express' => 'Ninja Express',
            'Grab Express' => 'Grab Express',
            'GoSend' => 'GoSend',
            'Lainnya' => 'Lainnya'
        ];

        $layananList = [
            'REG' => 'Reguler (REG)',
            'YES' => 'Yes (YES)',
            'OKE' => 'Oke (OKE)',
            'SDS' => 'Same Day Service (SDS)',
            'ONS' => 'Over Night Service (ONS)',
            'HDS' => 'Holiday Delivery Service (HDS)',
            'STANDARD' => 'Standard',
            'EXPRESS' => 'Express',
            'SAME_DAY' => 'Same Day',
        ];

        return view('admin.pengiriman.create', compact('pemesanan', 'ekspedisiList', 'layananList'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_pemesanan' => 'required|exists:pemesanan,id_pemesanan',
            'nama_penerima' => 'required|string|max:255',
            'no_hp_penerima' => 'required|string|max:20',
            'alamat_tujuan' => 'required|string',
            'ekspedisi' => 'required|string|max:255',
            'layanan' => 'required|string|max:255',
            'biaya_ongkir' => 'required|numeric|min:0',
            'no_resi' => 'nullable|string|max:100',
            'status_pengiriman' => 'required|in:menunggu,dikirim,diterima,gagal',
            'tanggal_dikirim' => 'nullable|date',
            'tanggal_diterima' => 'nullable|date',
        ]);

        DB::beginTransaction();

        try {
            // Cek apakah pemesanan sudah memiliki pengiriman yang aktif
            $existingPengiriman = Pengiriman::where('id_pemesanan', $request->id_pemesanan)
                ->whereNotIn('status_pengiriman', [Pengiriman::STATUS_GAGAL])
                ->first();

            if ($existingPengiriman) {
                return redirect()->back()
                    ->with('error', 'Pemesanan ini sudah memiliki pengiriman yang aktif.')
                    ->withInput();
            }

            $pengiriman = Pengiriman::create([
                'id_pemesanan' => $request->id_pemesanan,
                'nama_penerima' => $request->nama_penerima,
                'no_hp_penerima' => $request->no_hp_penerima,
                'alamat_tujuan' => $request->alamat_tujuan,
                'ekspedisi' => $request->ekspedisi,
                'layanan' => $request->layanan,
                'biaya_ongkir' => $request->biaya_ongkir,
                'no_resi' => $request->no_resi,
                'status_pengiriman' => $request->status_pengiriman,
                'tanggal_dikirim' => $request->tanggal_dikirim,
                'tanggal_diterima' => $request->tanggal_diterima,
            ]);

            // Update status pemesanan berdasarkan status pengiriman
            $this->updatePemesananStatus($pengiriman, $request->status_pengiriman);

            DB::commit();

            return redirect()->route('pengiriman.index')
                ->with('success', 'Pengiriman berhasil dibuat.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $pengiriman = Pengiriman::with([
            'pemesanan.customer',
            'pemesanan.detailPemesanan.detailUkuran.produk'
        ])->findOrFail($id);

        return view('admin.pengiriman.show', compact('pengiriman'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $pengiriman = Pengiriman::with('pemesanan')->findOrFail($id);
        
        $ekspedisiList = [
            'JNE' => 'JNE',
            'TIKI' => 'TIKI',
            'POS Indonesia' => 'POS Indonesia',
            'J&T' => 'J&T',
            'Sicepat' => 'Sicepat',
            'Anteraja' => 'Anteraja',
            'Ninja Express' => 'Ninja Express',
            'Grab Express' => 'Grab Express',
            'GoSend' => 'GoSend',
            'Lainnya' => 'Lainnya'
        ];

        $layananList = [
            'REG' => 'Reguler (REG)',
            'YES' => 'Yes (YES)',
            'OKE' => 'Oke (OKE)',
            'SDS' => 'Same Day Service (SDS)',
            'ONS' => 'Over Night Service (ONS)',
            'HDS' => 'Holiday Delivery Service (HDS)',
            'STANDARD' => 'Standard',
            'EXPRESS' => 'Express',
            'SAME_DAY' => 'Same Day',
        ];

        $statusPengiriman = [
            Pengiriman::STATUS_MENUNGGU => 'Menunggu Pengiriman',
            Pengiriman::STATUS_DIKIRIM => 'Sedang Dikirim',
            Pengiriman::STATUS_DITERIMA => 'Sudah Diterima',
            Pengiriman::STATUS_GAGAL => 'Gagal Dikirim',
        ];

        return view('admin.pengiriman.edit', compact('pengiriman', 'ekspedisiList', 'layananList', 'statusPengiriman'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Jika hanya update status (quick action dari show page)
        if ($request->has('update_only_status')) {
            return $this->updateStatus($request, $id);
        }

        // Jika mark as shipped dengan nomor resi
        if ($request->has('mark_as_shipped')) {
            return $this->markAsShipped($request, $id);
        }

        // Update biasa
        $request->validate([
            'nama_penerima' => 'required|string|max:255',
            'no_hp_penerima' => 'required|string|max:20',
            'alamat_tujuan' => 'required|string',
            'ekspedisi' => 'required|string|max:255',
            'layanan' => 'required|string|max:255',
            'biaya_ongkir' => 'required|numeric|min:0',
            'no_resi' => 'nullable|string|max:100',
            'status_pengiriman' => 'required|in:menunggu,dikirim,diterima,gagal',
            'tanggal_dikirim' => 'nullable|date',
            'tanggal_diterima' => 'nullable|date',
        ]);

        DB::beginTransaction();

        try {
            $pengiriman = Pengiriman::with('pemesanan')->findOrFail($id);
            $oldStatus = $pengiriman->status_pengiriman;

            $updateData = [
                'nama_penerima' => $request->nama_penerima,
                'no_hp_penerima' => $request->no_hp_penerima,
                'alamat_tujuan' => $request->alamat_tujuan,
                'ekspedisi' => $request->ekspedisi,
                'layanan' => $request->layanan,
                'biaya_ongkir' => $request->biaya_ongkir,
                'no_resi' => $request->no_resi,
                'status_pengiriman' => $request->status_pengiriman,
            ];

            // Auto-set tanggal jika status berubah
            if ($request->status_pengiriman === Pengiriman::STATUS_DIKIRIM && !$request->tanggal_dikirim) {
                $updateData['tanggal_dikirim'] = now();
            } else {
                $updateData['tanggal_dikirim'] = $request->tanggal_dikirim;
            }

            if ($request->status_pengiriman === Pengiriman::STATUS_DITERIMA && !$request->tanggal_diterima) {
                $updateData['tanggal_diterima'] = now();
            } else {
                $updateData['tanggal_diterima'] = $request->tanggal_diterima;
            }

            $pengiriman->update($updateData);

            // Update status pemesanan jika status pengiriman berubah
            if ($oldStatus !== $request->status_pengiriman) {
                $this->updatePemesananStatus($pengiriman, $request->status_pengiriman);
            }

            DB::commit();

            return redirect()->route('pengiriman.show', $pengiriman->id_pengiriman)
                ->with('success', 'Pengiriman berhasil diperbarui.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        DB::beginTransaction();

        try {
            $pengiriman = Pengiriman::findOrFail($id);
            $pengiriman->delete();

            DB::commit();

            return redirect()->route('pengiriman.index')
                ->with('success', 'Pengiriman berhasil dihapus.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Update status pengiriman (internal method)
     */
    private function updateStatus(Request $request, string $id)
    {
        $request->validate([
            'status_pengiriman' => 'required|in:menunggu,dikirim,diterima,gagal'
        ]);

        DB::beginTransaction();

        try {
            $pengiriman = Pengiriman::with('pemesanan')->findOrFail($id);
            $oldStatus = $pengiriman->status_pengiriman;

            $updateData = [
                'status_pengiriman' => $request->status_pengiriman,
            ];

            // Auto-set tanggal jika status berubah
            if ($request->status_pengiriman === Pengiriman::STATUS_DIKIRIM && !$pengiriman->tanggal_dikirim) {
                $updateData['tanggal_dikirim'] = now();
            }

            if ($request->status_pengiriman === Pengiriman::STATUS_DITERIMA && !$pengiriman->tanggal_diterima) {
                $updateData['tanggal_diterima'] = now();
            }

            $pengiriman->update($updateData);

            // Update status pemesanan
            $this->updatePemesananStatus($pengiriman, $request->status_pengiriman);

            DB::commit();

            return redirect()->back()
                ->with('success', 'Status pengiriman berhasil diubah dari ' . $oldStatus . ' menjadi ' . $request->status_pengiriman);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Mark as shipped (internal method)
     */
    private function markAsShipped(Request $request, string $id)
    {
        $request->validate([
            'no_resi' => 'required|string|max:100'
        ]);

        DB::beginTransaction();

        try {
            $pengiriman = Pengiriman::with('pemesanan')->findOrFail($id);
            
            $pengiriman->markAsShipped($request->no_resi);

            DB::commit();

            return redirect()->back()
                ->with('success', 'Pengiriman berhasil ditandai sebagai dikirim dengan nomor resi: ' . $request->no_resi);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Track shipment - tambahkan method khusus untuk tracking
     */
    public function track(string $id)
    {
        $pengiriman = Pengiriman::findOrFail($id);

        if (!$pengiriman->hasTrackingNumber()) {
            return redirect()->back()
                ->with('error', 'Nomor resi tidak tersedia untuk tracking.');
        }

        $trackingUrl = $pengiriman->tracking_url;

        if ($trackingUrl) {
            return redirect()->away($trackingUrl);
        }

        return redirect()->back()
            ->with('error', 'Tidak dapat menemukan URL tracking untuk ekspedisi ' . $pengiriman->ekspedisi);
    }

    /**
     * Update status pemesanan berdasarkan status pengiriman
     */
    private function updatePemesananStatus(Pengiriman $pengiriman, $statusPengiriman)
    {
        if (!$pengiriman->pemesanan) {
            return;
        }

        $pemesanan = $pengiriman->pemesanan;

        switch ($statusPengiriman) {
            case Pengiriman::STATUS_DIKIRIM:
                if ($pemesanan->status === Pemesanan::STATUS_DIPROSES) {
                    $pemesanan->update(['status' => Pemesanan::STATUS_DIKIRIM]);
                }
                break;
                
            case Pengiriman::STATUS_DITERIMA:
                if ($pemesanan->status === Pemesanan::STATUS_DIKIRIM) {
                    $pemesanan->update(['status' => Pemesanan::STATUS_SELESAI]);
                }
                break;
                
            case Pengiriman::STATUS_GAGAL:
                if (in_array($pemesanan->status, [Pemesanan::STATUS_DIPROSES, Pemesanan::STATUS_DIKIRIM])) {
                    $pemesanan->update(['status' => Pemesanan::STATUS_BATAL]);
                }
                break;
        }
    }

    /**
     * Filter shipments
     */
    public function filter(Request $request)
    {
        try {
            $status = $request->input('status', '');
            $ekspedisi = $request->input('ekspedisi', '');
            $sortBy = $request->input('sort_by', 'terbaru');
            $minOngkir = $request->input('min_ongkir', '');
            $maxOngkir = $request->input('max_ongkir', '');
            
            Log::debug('Memulai proses filter pengiriman', [
                'status' => $status,
                'ekspedisi' => $ekspedisi,
                'sort_by' => $sortBy,
                'min_ongkir' => $minOngkir,
                'max_ongkir' => $maxOngkir
            ]);

            // Base query dengan eager loading
            $query = Pengiriman::with(['pemesanan']);

            // Filter by status
            if (!empty($status) && $status !== 'semua') {
                $query->where('status_pengiriman', $status);
            }

            // Filter by ekspedisi
            if (!empty($ekspedisi) && $ekspedisi !== 'semua') {
                $query->where('ekspedisi', $ekspedisi);
            }

            // Filter by ongkir range
            if (!empty($minOngkir)) {
                $query->where('biaya_ongkir', '>=', $minOngkir);
            }
            
            if (!empty($maxOngkir)) {
                $query->where('biaya_ongkir', '<=', $maxOngkir);
            }

            // Sorting
            switch ($sortBy) {
                case 'terlama':
                    $query->orderBy('created_at', 'asc');
                    break;
                case 'ongkir_tertinggi':
                    $query->orderBy('biaya_ongkir', 'desc');
                    break;
                case 'ongkir_terendah':
                    $query->orderBy('biaya_ongkir', 'asc');
                    break;
                case 'tanggal_dikirim_asc':
                    $query->orderBy('tanggal_dikirim', 'asc');
                    break;
                case 'tanggal_dikirim_desc':
                    $query->orderBy('tanggal_dikirim', 'desc');
                    break;
                case 'tanggal_diterima_asc':
                    $query->orderBy('tanggal_diterima', 'asc');
                    break;
                case 'tanggal_diterima_desc':
                    $query->orderBy('tanggal_diterima', 'desc');
                    break;
                case 'terbaru':
                default:
                    $query->orderBy('created_at', 'desc');
                    break;
            }

            $pengiriman = $query->paginate(10);

            Log::debug('Filter pengiriman berhasil', [
                'jumlah_ditemukan' => $pengiriman->total(),
                'status' => $status,
                'ekspedisi' => $ekspedisi
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'html' => view('admin.pengiriman.partials.pengiriman-table', compact('pengiriman'))->render(),
                    'pagination' => (string) $pengiriman->links(),
                    'total' => $pengiriman->total()
                ]);
            }

            return view('admin.pengiriman.index', compact('pengiriman', 'status', 'ekspedisi', 'sortBy', 'minOngkir', 'maxOngkir'));

        } catch (\Exception $e) {
            Log::error('Error pada filter pengiriman: ' . $e->getMessage(), [
                'status' => $status ?? '',
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat memfilter pengiriman'
                ], 500);
            }

            return redirect()->route('admin.pengiriman.index')
                ->with('error', 'Terjadi kesalahan saat memfilter pengiriman: ' . $e->getMessage());
        }
    }
}