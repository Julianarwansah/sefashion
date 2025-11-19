<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use App\Models\Pemesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PembayaranController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pembayaran = Pembayaran::with('pemesanan.customer')
            ->latest('created_at')
            ->get();

        return view('admin.pembayaran.index', compact('pembayaran'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pemesanan = Pemesanan::whereDoesntHave('pembayaran')
            ->orWhereHas('pembayaran', function($query) {
                $query->whereIn('status_pembayaran', [Pembayaran::STATUS_GAGAL, Pembayaran::STATUS_EXPIRED]);
            })
            ->with('customer')
            ->get();

        $metodePembayaran = [
            Pembayaran::METHOD_TRANSFER => 'Transfer Bank',
            Pembayaran::METHOD_COD => 'Cash on Delivery',
            Pembayaran::METHOD_EWALLET => 'E-Wallet',
            Pembayaran::METHOD_VA => 'Virtual Account',
            Pembayaran::METHOD_CREDIT_CARD => 'Kartu Kredit',
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
            'metode_pembayaran' => 'required|in:transfer,cod,ewallet,va,credit_card',
            'channel' => 'nullable|string|max:255',
            'jumlah_bayar' => 'required|numeric|min:0',
            'status_pembayaran' => 'required|in:belum_bayar,menunggu,sudah_bayar,gagal,expired,refund',
            'tanggal_pembayaran' => 'nullable|date',
        ]);

        DB::beginTransaction();

        try {
            // Cek apakah pemesanan sudah memiliki pembayaran yang aktif
            $existingPembayaran = Pembayaran::where('id_pemesanan', $request->id_pemesanan)
                ->whereNotIn('status_pembayaran', [Pembayaran::STATUS_GAGAL, Pembayaran::STATUS_EXPIRED])
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
                'tanggal_pembayaran' => $request->tanggal_pembayaran,
                'external_id' => 'INV-' . time() . '-' . rand(1000, 9999),
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
            Pembayaran::METHOD_TRANSFER => 'Transfer Bank',
            Pembayaran::METHOD_COD => 'Cash on Delivery',
            Pembayaran::METHOD_EWALLET => 'E-Wallet',
            Pembayaran::METHOD_VA => 'Virtual Account',
            Pembayaran::METHOD_CREDIT_CARD => 'Kartu Kredit',
        ];

        $statusPembayaran = [
            Pembayaran::STATUS_BELUM_BAYAR => 'Belum Bayar',
            Pembayaran::STATUS_MENUNGGU => 'Menunggu Konfirmasi',
            Pembayaran::STATUS_SUDAH_BAYAR => 'Sudah Bayar',
            Pembayaran::STATUS_GAGAL => 'Gagal',
            Pembayaran::STATUS_EXPIRED => 'Kadaluarsa',
            Pembayaran::STATUS_REFUND => 'Refund',
        ];

        return view('admin.pembayaran.edit', compact('pembayaran', 'metodePembayaran', 'statusPembayaran'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'metode_pembayaran' => 'required|in:transfer,cod,ewallet,va,credit_card',
            'channel' => 'nullable|string|max:255',
            'jumlah_bayar' => 'required|numeric|min:0',
            'status_pembayaran' => 'required|in:belum_bayar,menunggu,sudah_bayar,gagal,expired,refund',
            'tanggal_pembayaran' => 'nullable|date',
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
                'tanggal_pembayaran' => $request->tanggal_pembayaran,
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
            'status_pembayaran' => 'required|in:belum_bayar,menunggu,sudah_bayar,gagal,expired,refund'
        ]);

        DB::beginTransaction();

        try {
            $pembayaran = Pembayaran::with('pemesanan')->findOrFail($id);
            $oldStatus = $pembayaran->status_pembayaran;

            $pembayaran->update([
                'status_pembayaran' => $request->status_pembayaran,
                'tanggal_pembayaran' => $request->status_pembayaran === Pembayaran::STATUS_SUDAH_BAYAR ? now() : $pembayaran->tanggal_pembayaran,
            ]);

            // Update status pemesanan
            $this->updatePemesananStatus($pembayaran, $request->status_pembayaran);

            DB::commit();

            return redirect()->back()
                ->with('success', 'Status pembayaran berhasil diubah dari ' . $oldStatus . ' menjadi ' . $request->status_pembayaran);

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
            
            $pembayaran->markAsPaid();

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
            
            $pembayaran->markAsExpired();

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
        \Log::info('Webhook received:', $request->all());

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

            $pembayaran->processWebhook($request->all());

            return response()->json(['message' => 'Webhook processed successfully']);

        } catch (\Exception $e) {
            \Log::error('Webhook error: ' . $e->getMessage());
            return response()->json(['error' => 'Internal server error'], 500);
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
            case Pembayaran::STATUS_EXPIRED:
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
                $query->whereIn('status_pembayaran', [Pembayaran::STATUS_GAGAL, Pembayaran::STATUS_EXPIRED]);
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
}