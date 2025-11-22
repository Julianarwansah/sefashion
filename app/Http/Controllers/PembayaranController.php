<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use App\Models\Pemesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PembayaranController extends Controller
{
    /**
     * Display a listing of the resource.
     */
   public function index()
    {
        try {
            Log::debug('Memulai proses mengambil data pembayaran');
            
            $pembayaran = Pembayaran::with(['pemesanan.customer'])
                ->latest('created_at', 'desc')
                ->orderBy('created_at', 'desc')
                ->paginate(10);

            Log::debug('Berhasil mengambil data pembayaran', [
                'jumlah' => $pembayaran->count(),
                'total' => $pembayaran->total()
            ]);
            
            return view('admin.pembayaran.index', compact('pembayaran'));
            
        } catch (\Exception $e) {
            Log::error('Error pada index pembayaran: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()->with('error', 'Terjadi kesalahan saat mengambil data pembayaran');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pemesanan = Pemesanan::whereDoesntHave('pembayaran')
            ->orWhereHas('pembayaran', function($query) {
                $query->whereIn('status_pembayaran', [Pembayaran::STATUS_GAGAL, Pembayaran::STATUS_KADALUARSA]);
            })
            ->with('customer')
            ->get();

        $metodePembayaran = [
            Pembayaran::METHOD_VA => 'Virtual Account',
            Pembayaran::METHOD_EWALLET => 'E-Wallet',
            Pembayaran::METHOD_RETAIL => 'Retail Outlet',
            Pembayaran::METHOD_COD => 'Cash on Delivery',
        ];

        return view('admin.pembayaran.create', compact('pemesanan', 'metodePembayaran'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_pemesanan' => 'required|exists:pemesanan,id_pemesanan',
            'metode_pembayaran' => 'required|in:va,ewallet,retail,cod',
            'channel' => 'nullable|string|max:255',
            'jumlah_bayar' => 'required|numeric|min:0',
            'status_pembayaran' => 'required|in:belum_bayar,menunggu,sudah_bayar,gagal,kadaluarsa',
        ]);

        DB::beginTransaction();

        try {
            // Cek apakah pemesanan sudah memiliki pembayaran yang aktif
            $existingPembayaran = Pembayaran::where('id_pemesanan', $request->id_pemesanan)
                ->whereNotIn('status_pembayaran', [Pembayaran::STATUS_GAGAL, Pembayaran::STATUS_KADALUARSA])
                ->first();

            if ($existingPembayaran) {
                return redirect()->back()
                    ->with('error', 'Pemesanan ini sudah memiliki pembayaran yang aktif.')
                    ->withInput();
            }

            $pembayaran = Pembayaran::create([
                'id_pemesanan' => $request->id_pemesanan,
                'metode_pembayaran' => $request->metode_pembayaran,
                'channel' => $request->channel,
                'jumlah_bayar' => $request->jumlah_bayar,
                'status_pembayaran' => $request->status_pembayaran,
                // external_id akan di-generate otomatis oleh model boot method
            ]);

            // Update status pemesanan jika pembayaran sudah berhasil
            if ($pembayaran->isPaid()) {
                $pemesanan = Pemesanan::find($request->id_pemesanan);
                if ($pemesanan->status === Pemesanan::STATUS_PENDING) {
                    $pemesanan->update(['status' => Pemesanan::STATUS_DIPROSES]);
                }
            }

            DB::commit();

            return redirect()->route('pembayaran.index')
                ->with('success', 'Pembayaran berhasil dibuat.');

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
        $pembayaran = Pembayaran::with([
            'pemesanan.customer',
            'pemesanan.detailPemesanan.detailUkuran.produk'
        ])->findOrFail($id);

        return view('admin.pembayaran.show', compact('pembayaran'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $pembayaran = Pembayaran::with('pemesanan')->findOrFail($id);
        
        $metodePembayaran = [
            Pembayaran::METHOD_VA => 'Virtual Account',
            Pembayaran::METHOD_EWALLET => 'E-Wallet',
            Pembayaran::METHOD_RETAIL => 'Retail Outlet',
            Pembayaran::METHOD_COD => 'Cash on Delivery',
        ];

        $statusPembayaran = [
            Pembayaran::STATUS_BELUM_BAYAR => 'Belum Bayar',
            Pembayaran::STATUS_MENUNGGU => 'Menunggu Konfirmasi',
            Pembayaran::STATUS_SUDAH_BAYAR => 'Sudah Bayar',
            Pembayaran::STATUS_GAGAL => 'Gagal',
            Pembayaran::STATUS_KADALUARSA => 'Kadaluarsa',
        ];

        return view('admin.pembayaran.edit', compact('pembayaran', 'metodePembayaran', 'statusPembayaran'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'metode_pembayaran' => 'required|in:va,ewallet,retail,cod',
            'channel' => 'nullable|string|max:255',
            'jumlah_bayar' => 'required|numeric|min:0',
            'status_pembayaran' => 'required|in:belum_bayar,menunggu,sudah_bayar,gagal,kadaluarsa',
        ]);

        DB::beginTransaction();

        try {
            $pembayaran = Pembayaran::with('pemesanan')->findOrFail($id);
            $oldStatus = $pembayaran->status_pembayaran;

            $pembayaran->update([
                'metode_pembayaran' => $request->metode_pembayaran,
                'channel' => $request->channel,
                'jumlah_bayar' => $request->jumlah_bayar,
                'status_pembayaran' => $request->status_pembayaran,
            ]);

            // Update status pemesanan jika status pembayaran berubah
            if ($oldStatus !== $request->status_pembayaran) {
                $this->updatePemesananStatus($pembayaran, $request->status_pembayaran);
            }

            DB::commit();

            return redirect()->route('pembayaran.show', $pembayaran->id_pembayaran)
                ->with('success', 'Pembayaran berhasil diperbarui.');

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
            $pembayaran = Pembayaran::findOrFail($id);
            $pembayaran->delete();

            DB::commit();

            return redirect()->route('pembayaran.index')
                ->with('success', 'Pembayaran berhasil dihapus.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Update status pembayaran
     */
    public function updateStatus(Request $request, string $id)
    {
        $request->validate([
            'status_pembayaran' => 'required|in:belum_bayar,menunggu,sudah_bayar,gagal,kadaluarsa'
        ]);

        DB::beginTransaction();

        try {
            $pembayaran = Pembayaran::with('pemesanan')->findOrFail($id);
            $oldStatus = $pembayaran->status_pembayaran;

            $pembayaran->update([
                'status_pembayaran' => $request->status_pembayaran,
            ]);

            // Update status pemesanan
            $this->updatePemesananStatus($pembayaran, $request->status_pembayaran);

            DB::commit();

            return redirect()->back()
                ->with('success', 'Status pembayaran berhasil diubah.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Mark as paid
     */
    public function markAsPaid(string $id)
    {
        DB::beginTransaction();

        try {
            $pembayaran = Pembayaran::with('pemesanan')->findOrFail($id);
            
            $pembayaran->updateStatus(Pembayaran::STATUS_SUDAH_BAYAR);

            DB::commit();

            return redirect()->back()
                ->with('success', 'Pembayaran berhasil ditandai sebagai sudah dibayar.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Mark as expired
     */
    public function markAsExpired(string $id)
    {
        DB::beginTransaction();

        try {
            $pembayaran = Pembayaran::findOrFail($id);
            
            $pembayaran->updateStatus(Pembayaran::STATUS_KADALUARSA);

            DB::commit();

            return redirect()->back()
                ->with('success', 'Pembayaran berhasil ditandai sebagai kadaluarsa.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Process webhook from payment gateway
     */
    public function webhook(Request $request)
    {
        Log::info('Webhook received:', $request->all());

        try {
            $externalId = $request->input('external_id');
            $status = $request->input('status');

            if (!$externalId) {
                return response()->json(['error' => 'External ID tidak ditemukan'], 400);
            }

            $pembayaran = Pembayaran::where('external_id', $externalId)->first();

            if (!$pembayaran) {
                return response()->json(['error' => 'Pembayaran tidak ditemukan'], 404);
            }

            // Process webhook data
            $this->processWebhookData($pembayaran, $request->all());

            return response()->json(['message' => 'Webhook processed successfully']);

        } catch (\Exception $e) {
            Log::error('Webhook error: ' . $e->getMessage());
            return response()->json(['error' => 'Internal server error'], 500);
        }
    }

    /**
     * Process webhook data dari payment gateway
     */
    private function processWebhookData(Pembayaran $pembayaran, array $data)
    {
        DB::beginTransaction();

        try {
            $status = $data['status'] ?? null;
            $xenditId = $data['id'] ?? null;
            
            // Update data pembayaran dari webhook
            $updateData = [
                'xendit_id' => $xenditId,
            ];

            // Map status dari payment gateway ke status internal
            $statusMapping = [
                'PENDING' => Pembayaran::STATUS_MENUNGGU,
                'PAID' => Pembayaran::STATUS_SUDAH_BAYAR,
                'EXPIRED' => Pembayaran::STATUS_KADALUARSA,
                'FAILED' => Pembayaran::STATUS_GAGAL,
            ];

            if ($status && array_key_exists($status, $statusMapping)) {
                $updateData['status_pembayaran'] = $statusMapping[$status];
            }

            // Update additional fields jika tersedia
            $additionalFields = [
                'xendit_external_id',
                'xendit_payment_url', 
                'xendit_expiry_date',
                'xendit_merchant_name',
                'xendit_account_number',
            ];

            foreach ($additionalFields as $field) {
                if (isset($data[$field])) {
                    $updateData[$field] = $data[$field];
                }
            }

            $pembayaran->update($updateData);

            // Update status pemesanan jika pembayaran berhasil
            if ($status === 'PAID' && $pembayaran->pemesanan) {
                $pembayaran->pemesanan->update(['status' => Pemesanan::STATUS_DIPROSES]);
            }

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Update status pemesanan berdasarkan status pembayaran
     */
    private function updatePemesananStatus(Pembayaran $pembayaran, $statusPembayaran)
    {
        if (!$pembayaran->pemesanan) {
            return;
        }

        $pemesanan = $pembayaran->pemesanan;

        switch ($statusPembayaran) {
            case Pembayaran::STATUS_SUDAH_BAYAR:
                if ($pemesanan->status === Pemesanan::STATUS_PENDING) {
                    $pemesanan->update(['status' => Pemesanan::STATUS_DIPROSES]);
                }
                break;
                
            case Pembayaran::STATUS_GAGAL:
            case Pembayaran::STATUS_KADALUARSA:
                if ($pemesanan->status === Pemesanan::STATUS_DIPROSES) {
                    $pemesanan->update(['status' => Pemesanan::STATUS_PENDING]);
                }
                break;
        }
    }

    /**
     * Get pemesanan data for select2
     */
    public function getPemesananData()
    {
        $pemesanan = Pemesanan::whereDoesntHave('pembayaran')
            ->orWhereHas('pembayaran', function($query) {
                $query->whereIn('status_pembayaran', [Pembayaran::STATUS_GAGAL, Pembayaran::STATUS_KADALUARSA]);
            })
            ->with('customer')
            ->get()
            ->map(function($item) {
                return [
                    'id' => $item->id_pemesanan,
                    'text' => '#' . $item->id_pemesanan . ' - ' . ($item->customer->nama_customer ?? 'N/A') . ' - ' . $item->total_harga_formatted,
                    'total_harga' => $item->total_harga
                ];
            });

        return response()->json($pemesanan);
    }

    /**
     * Filter payments
     */
    public function filter(Request $request)
    {
        try {
            $status = $request->input('status', '');
            $metode = $request->input('metode', '');
            $sortBy = $request->input('sort_by', 'terbaru');
            $minAmount = $request->input('min_amount', '');
            $maxAmount = $request->input('max_amount', '');
            
            Log::debug('Memulai proses filter pembayaran', [
                'status' => $status,
                'metode' => $metode,
                'sort_by' => $sortBy,
                'min_amount' => $minAmount,
                'max_amount' => $maxAmount
            ]);

            // Base query dengan eager loading
            $query = Pembayaran::with(['pemesanan.customer']);

            // Filter by status
            if (!empty($status) && $status !== 'semua') {
                $query->where('status_pembayaran', $status);
            }

            // Filter by amount range
            if (!empty($minAmount)) {
                $query->where('jumlah_bayar', '>=', $minAmount);
            }
            
            if (!empty($maxAmount)) {
                $query->where('jumlah_bayar', '<=', $maxAmount);
            }

            // Sorting
            switch ($sortBy) {
                case 'terlama':
                    $query->orderBy('created_at', 'asc');
                    break;
                case 'jumlah_tertinggi':
                    $query->orderBy('jumlah_bayar', 'desc');
                    break;
                case 'jumlah_terendah':
                    $query->orderBy('jumlah_bayar', 'asc');
                    break;
                case 'tanggal_pembayaran_asc':
                    $query->orderBy('created_at', 'asc');
                    break;
                case 'tanggal_pembayaran_desc':
                    $query->orderBy('created_at', 'desc');
                    break;
                case 'terbaru':
                default:
                    $query->orderBy('created_at', 'desc');
                    break;
            }

            $pembayaran = $query->paginate(10);

            Log::debug('Filter pembayaran berhasil', [
                'jumlah_ditemukan' => $pembayaran->total(),
                'status' => $status
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'html' => view('admin.pembayaran.partials.pembayaran-table', compact('pembayaran'))->render(),
                    'pagination' => (string) $pembayaran->links(),
                    'total' => $pembayaran->total()
                ]);
            }

            return view('admin.pembayaran.index', compact('pembayaran', 'status', 'sortBy', 'minAmount', 'maxAmount'));

        } catch (\Exception $e) {
            Log::error('Error pada filter pembayaran: ' . $e->getMessage(), [
                'status' => $status ?? '',
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat memfilter pembayaran'
                ], 500);
            }

            return redirect()->route('admin.pembayaran.index')
                ->with('error', 'Terjadi kesalahan saat memfilter pembayaran: ' . $e->getMessage());
        }
    }
}