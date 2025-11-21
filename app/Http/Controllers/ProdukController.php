<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\DetailWarna;
use App\Models\DetailUkuran;
use App\Models\ProdukGambar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProdukController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            Log::debug('Memulai proses mengambil data produk');
            
            $produk = Produk::with([
                'detailWarna',
                'detailUkuran',
                'gambarProduk'
            ])->latest()->get();

            Log::debug('Berhasil mengambil data produk', ['jumlah' => $produk->count()]);
            
            return view('admin.produk.index', compact('produk'));
            
        } catch (\Exception $e) {
            Log::error('Error pada index produk: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()->with('error', 'Terjadi kesalahan saat mengambil data produk');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            Log::debug('Memulai proses menampilkan form create produk');
            
            return view('admin.produk.create');
            
        } catch (\Exception $e) {
            Log::error('Error pada create produk: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menampilkan form tambah produk');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Log::debug('Memulai proses store produk', ['request_data' => $request->except(['_token', 'gambar', 'gambar_produk'])]);
        
        DB::beginTransaction();
        
        try {
            // Validasi dasar produk
            $validated = $request->validate([
                'nama_produk' => 'required|string|max:100',
                'deskripsi' => 'nullable|string',
                'kategori' => 'nullable|string|max:50',
                'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            ]);

            Log::debug('Validasi dasar produk berhasil', ['validated_data_keys' => array_keys($validated)]);

            // Validasi warna
            $warnaValidated = $request->validate([
                'warna' => 'required|array|min:1',
                'warna.*.nama_warna' => 'required|string|max:100',
                'warna.*.kode_warna' => 'nullable|string|max:50',
            ]);

            // Validasi ukuran
            $ukuranValidated = $request->validate([
                'ukuran' => 'required|array|min:1',
                'ukuran.*.id_warna_index' => 'required|integer|min:0',
                'ukuran.*.ukuran' => 'required|string|max:50',
                'ukuran.*.harga' => 'required|numeric|min:0',
                'ukuran.*.stok' => 'required|integer|min:0',
                'ukuran.*.tambahan' => 'nullable|string',
            ]);

            // Validasi gambar produk tambahan
            $gambarRules = [];
            if ($request->has('gambar_produk')) {
                foreach ($request->gambar_produk as $index => $gambarGroup) {
                    if (isset($gambarGroup['gambar']) && is_array($gambarGroup['gambar'])) {
                        foreach ($gambarGroup['gambar'] as $fileIndex => $file) {
                            if ($file) {
                                $gambarRules["gambar_produk.{$index}.gambar.{$fileIndex}"] = 'image|mimes:jpeg,png,jpg,gif,webp|max:2048';
                            }
                        }
                    }
                }
            }

            if (!empty($gambarRules)) {
                $request->validate($gambarRules);
            }

            Log::debug('Semua validasi berhasil');

            // 1. Create produk terlebih dahulu
            $produkData = [
                'nama_produk' => $validated['nama_produk'],
                'deskripsi' => $validated['deskripsi'] ?? null,
                'kategori' => $validated['kategori'] ?? null,
                'total_stok' => 0
            ];

            // Handle gambar utama
            if ($request->hasFile('gambar')) {
                $gambarPath = $request->file('gambar')->store('produk', 'public');
                $produkData['gambar'] = basename($gambarPath);
                Log::debug('Gambar utama berhasil diupload', ['path' => $gambarPath]);
            }

            $produk = Produk::create($produkData);
            Log::debug('Produk berhasil dibuat', ['id_produk' => $produk->id_produk]);

            // 2. Create warna-warna untuk produk ini
            $warnaIds = [];
            foreach ($warnaValidated['warna'] as $index => $warnaData) {
                $warna = DetailWarna::create([
                    'id_produk' => $produk->id_produk,
                    'nama_warna' => $warnaData['nama_warna'],
                    'kode_warna' => $warnaData['kode_warna'] ?? null
                ]);
                $warnaIds[$index] = $warna->id_warna;
                Log::debug('Warna berhasil dibuat', [
                    'id_warna' => $warna->id_warna, 
                    'nama_warna' => $warna->nama_warna,
                    'index' => $index
                ]);
            }

            // 3. Create ukuran-ukuran dengan mapping id_warna yang benar
            $totalStok = 0;
            foreach ($ukuranValidated['ukuran'] as $index => $ukuranData) {
                $idWarnaIndex = $ukuranData['id_warna_index'];
                
                if (!isset($warnaIds[$idWarnaIndex])) {
                    throw new \Exception("Index warna tidak valid: {$idWarnaIndex}");
                }

                $ukuran = DetailUkuran::create([
                    'id_produk' => $produk->id_produk,
                    'id_warna' => $warnaIds[$idWarnaIndex],
                    'ukuran' => $ukuranData['ukuran'],
                    'harga' => $ukuranData['harga'],
                    'stok' => $ukuranData['stok'],
                    'tambahan' => $ukuranData['tambahan'] ?? null
                ]);
                $totalStok += $ukuranData['stok'];
                Log::debug('Ukuran berhasil dibuat', [
                    'id_ukuran' => $ukuran->id_ukuran, 
                    'ukuran' => $ukuran->ukuran,
                    'id_warna' => $ukuran->id_warna,
                    'stok' => $ukuran->stok
                ]);
            }

            // 4. Update total stok produk
            $produk->update(['total_stok' => $totalStok]);
            Log::debug('Total stok diupdate', ['total_stok' => $totalStok]);

            // 5. Handle gambar produk tambahan - SUPPORT MULTIPLE GAMBAR
            $hasPrimary = false;
            
            if ($request->has('gambar_produk')) {
                foreach ($request->gambar_produk as $groupIndex => $gambarGroup) {
                    if (isset($gambarGroup['gambar']) && is_array($gambarGroup['gambar'])) {
                        $idWarna = null;
                        
                        // Jika ada id_warna_index, dapatkan id_warna yang sebenarnya
                        if (isset($gambarGroup['id_warna_index']) && $gambarGroup['id_warna_index'] !== '') {
                            $warnaIndex = $gambarGroup['id_warna_index'];
                            if (isset($warnaIds[$warnaIndex])) {
                                $idWarna = $warnaIds[$warnaIndex];
                            }
                        }

                        // Upload semua gambar dalam group
                        foreach ($gambarGroup['gambar'] as $fileIndex => $gambar) {
                            if ($gambar && $gambar->isValid()) {
                                $gambarPath = $gambar->store('produk/images', 'public');
                                
                                $isPrimary = false;
                                // Set gambar pertama sebagai primary jika belum ada primary
                                if (!$hasPrimary) {
                                    $isPrimary = true;
                                    $hasPrimary = true;
                                }
                                
                                ProdukGambar::create([
                                    'id_produk' => $produk->id_produk,
                                    'id_warna' => $idWarna,
                                    'id_ukuran' => null,
                                    'gambar' => basename($gambarPath),
                                    'is_primary' => $isPrimary
                                ]);
                                
                                Log::debug('Gambar produk berhasil diupload', [
                                    'path' => $gambarPath, 
                                    'group_index' => $groupIndex,
                                    'file_index' => $fileIndex,
                                    'id_warna' => $idWarna,
                                    'is_primary' => $isPrimary
                                ]);
                            }
                        }
                    }
                }
            }

            // 6. Jika tidak ada gambar tambahan dan ada gambar utama, set gambar utama sebagai primary
            if (!$hasPrimary && $produk->gambar) {
                // Convert gambar utama ke produk_gambar
                ProdukGambar::create([
                    'id_produk' => $produk->id_produk,
                    'id_warna' => null,
                    'id_ukuran' => null,
                    'gambar' => $produk->gambar,
                    'is_primary' => true
                ]);
                Log::debug('Gambar utama ditambahkan ke tabel produk_gambar sebagai primary');
            }

            DB::commit();
            Log::debug('Semua transaksi berhasil disimpan');

            return redirect()->route('admin.produk.index')
                ->with('success', 'Produk berhasil ditambahkan');

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Error pada store produk: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menambah produk: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            Log::debug('Memulai proses show produk', ['id_produk' => $id]);
            
            $produk = Produk::with([
                'detailWarna.detailUkuran',
                'detailUkuran',
                'gambarProduk'
            ])->findOrFail($id);

            Log::debug('Berhasil mengambil data produk untuk show', ['id_produk' => $produk->id_produk]);
            
            return view('admin.produk.show', compact('produk'));
            
        } catch (\Exception $e) {
            Log::error('Error pada show produk: ' . $e->getMessage(), [
                'id_produk' => $id,
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            
            return redirect()->back()->with('error', 'Produk tidak ditemukan');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        try {
            Log::debug('Memulai proses edit produk', ['id_produk' => $id]);
            
            $produk = Produk::with([
                'detailWarna.detailUkuran',
                'detailUkuran',
                'gambarProduk'
            ])->findOrFail($id);

            Log::debug('Berhasil mengambil data produk untuk edit', ['id_produk' => $produk->id_produk]);
            
            return view('admin.produk.edit', compact('produk'));
            
        } catch (\Exception $e) {
            Log::error('Error pada edit produk: ' . $e->getMessage(), [
                'id_produk' => $id,
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            
            return redirect()->back()->with('error', 'Produk tidak ditemukan');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
{
    Log::debug('=== DEBUG UPDATE PRODUK ===', [
        'id_produk' => $id,
        'warna_data' => $request->warna,
        'ukuran_data' => $request->ukuran,
        'all_request' => $request->all()
    ]);
    
    DB::beginTransaction();
    
    try {
        $produk = Produk::findOrFail($id);
        
        // Validasi dasar produk
        $validated = $request->validate([
            'nama_produk' => 'required|string|max:100',
            'deskripsi' => 'nullable|string',
            'kategori' => 'nullable|string|max:50',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        // Validasi warna
        $warnaValidated = $request->validate([
            'warna' => 'required|array|min:1',
            'warna.*.id_warna' => 'sometimes|nullable|integer',
            'warna.*.nama_warna' => 'required|string|max:100',
            'warna.*.kode_warna' => 'nullable|string|max:50',
        ]);

        // Validasi ukuran
        $ukuranValidated = $request->validate([
            'ukuran' => 'required|array|min:1',
            'ukuran.*.id_ukuran' => 'sometimes|nullable|integer',
            'ukuran.*.id_warna' => 'required|integer',
            'ukuran.*.ukuran' => 'required|string|max:50',
            'ukuran.*.harga' => 'required|numeric|min:0',
            'ukuran.*.stok' => 'required|integer|min:0',
            'ukuran.*.tambahan' => 'nullable|string',
        ]);

        Log::debug('Data setelah validasi:', [
            'warna_count' => count($warnaValidated['warna']),
            'ukuran_count' => count($ukuranValidated['ukuran']),
            'warna_ids' => collect($warnaValidated['warna'])->pluck('id_warna'),
            'ukuran_ids' => collect($ukuranValidated['ukuran'])->pluck('id_ukuran')
        ]);

        // Update produk
        $produkData = [
            'nama_produk' => $validated['nama_produk'],
            'deskripsi' => $validated['deskripsi'] ?? null,
            'kategori' => $validated['kategori'] ?? null
        ];

        // Handle gambar utama update
        if ($request->hasFile('gambar')) {
            if ($produk->gambar) {
                Storage::disk('public')->delete('produk/' . $produk->gambar);
            }
            
            $gambarPath = $request->file('gambar')->store('produk', 'public');
            $produkData['gambar'] = basename($gambarPath);
        }

        $produk->update($produkData);

        // PERBAIKAN: Handle warna dengan lebih hati-hati
        $existingWarnaIds = [];
        
        foreach ($warnaValidated['warna'] as $index => $warnaData) {
            Log::debug("Processing warna index {$index}:", $warnaData);
            
            if (!empty($warnaData['id_warna']) && $warnaData['id_warna'] != '') {
                // Update existing warna
                $warna = DetailWarna::where('id_warna', $warnaData['id_warna'])
                    ->where('id_produk', $id)
                    ->first();
                
                if ($warna) {
                    $warna->update([
                        'nama_warna' => $warnaData['nama_warna'],
                        'kode_warna' => $warnaData['kode_warna'] ?? null
                    ]);
                    $existingWarnaIds[] = (int)$warnaData['id_warna'];
                    Log::debug("Updated existing warna: {$warna->id_warna}");
                } else {
                    Log::debug("Warna not found: {$warnaData['id_warna']}");
                }
            } else {
                // Create new warna
                $newWarna = DetailWarna::create([
                    'id_produk' => $id,
                    'nama_warna' => $warnaData['nama_warna'],
                    'kode_warna' => $warnaData['kode_warna'] ?? null
                ]);
                $existingWarnaIds[] = $newWarna->id_warna;
                Log::debug("Created new warna: {$newWarna->id_warna}");
            }
        }

        Log::debug('Existing warna IDs to keep:', $existingWarnaIds);

        // Hanya delete jika ada warna yang perlu dihapus
        if (!empty($existingWarnaIds)) {
            $deletedWarna = DetailWarna::where('id_produk', $id)
                ->whereNotIn('id_warna', $existingWarnaIds)
                ->delete();
            Log::debug("Deleted {$deletedWarna} warna records");
        }

        // PERBAIKAN: Handle ukuran dengan lebih hati-hati
        $totalStok = 0;
        $existingUkuranIds = [];
        
        foreach ($ukuranValidated['ukuran'] as $index => $ukuranData) {
            Log::debug("Processing ukuran index {$index}:", $ukuranData);
            
            // Validasi bahwa id_warna ada di existingWarnaIds
            if (!in_array((int)$ukuranData['id_warna'], $existingWarnaIds)) {
                Log::error("Invalid id_warna in ukuran: {$ukuranData['id_warna']}");
                continue;
            }

            if (!empty($ukuranData['id_ukuran']) && $ukuranData['id_ukuran'] != '') {
                // Update existing ukuran
                $ukuran = DetailUkuran::where('id_ukuran', $ukuranData['id_ukuran'])
                    ->where('id_produk', $id)
                    ->first();
                
                if ($ukuran) {
                    $ukuran->update([
                        'id_warna' => $ukuranData['id_warna'],
                        'ukuran' => $ukuranData['ukuran'],
                        'harga' => $ukuranData['harga'],
                        'stok' => $ukuranData['stok'],
                        'tambahan' => $ukuranData['tambahan'] ?? null
                    ]);
                    $totalStok += $ukuranData['stok'];
                    $existingUkuranIds[] = (int)$ukuranData['id_ukuran'];
                    Log::debug("Updated existing ukuran: {$ukuran->id_ukuran}");
                } else {
                    Log::debug("Ukuran not found: {$ukuranData['id_ukuran']}");
                }
            } else {
                // Create new ukuran
                $newUkuran = DetailUkuran::create([
                    'id_produk' => $id,
                    'id_warna' => $ukuranData['id_warna'],
                    'ukuran' => $ukuranData['ukuran'],
                    'harga' => $ukuranData['harga'],
                    'stok' => $ukuranData['stok'],
                    'tambahan' => $ukuranData['tambahan'] ?? null
                ]);
                $totalStok += $ukuranData['stok'];
                $existingUkuranIds[] = $newUkuran->id_ukuran;
                Log::debug("Created new ukuran: {$newUkuran->id_ukuran}");
            }
        }

        Log::debug('Existing ukuran IDs to keep:', $existingUkuranIds);

        // Hanya delete jika ada ukuran yang perlu dihapus
        if (!empty($existingUkuranIds)) {
            $deletedUkuran = DetailUkuran::where('id_produk', $id)
                ->whereNotIn('id_ukuran', $existingUkuranIds)
                ->delete();
            Log::debug("Deleted {$deletedUkuran} ukuran records");
        }

        // Update total stok
        $produk->update(['total_stok' => $totalStok]);
        Log::debug("Updated total stok: {$totalStok}");

        DB::commit();
        Log::debug('=== UPDATE PRODUK BERHASIL ===');

        return redirect()->route('admin.produk.index')
            ->with('success', 'Produk berhasil diperbarui');

    } catch (\Exception $e) {
        DB::rollBack();
        
        Log::error('Error pada update produk: ' . $e->getMessage(), [
            'id_produk' => $id,
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString()
        ]);
        
        return redirect()->back()
            ->with('error', 'Terjadi kesalahan saat mengupdate produk: ' . $e->getMessage())
            ->withInput();
    }
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Log::debug('Memulai proses destroy produk', ['id_produk' => $id]);
        
        DB::beginTransaction();
        
        try {
            $produk = Produk::with(['detailWarna', 'detailUkuran', 'gambarProduk'])->findOrFail($id);
            
            Log::debug('Produk ditemukan untuk dihapus', [
                'id_produk' => $produk->id_produk,
                'nama_produk' => $produk->nama_produk
            ]);

            // Delete gambar utama
            if ($produk->gambar) {
                Storage::disk('public')->delete('produk/' . $produk->gambar);
                Log::debug('Gambar utama dihapus', ['gambar' => $produk->gambar]);
            }

            // Delete gambar produk
            foreach ($produk->gambarProduk as $gambar) {
                Storage::disk('public')->delete('produk/images/' . $gambar->gambar);
                Log::debug('Gambar produk dihapus', ['gambar' => $gambar->gambar]);
            }

            // Delete akan otomatis cascade karena foreign key constraints
            $produk->delete();
            Log::debug('Produk dan data terkait berhasil dihapus');

            DB::commit();
            Log::debug('Semua transaksi delete berhasil');

            return redirect()->route('admin.produk.index')
                ->with('success', 'Produk berhasil dihapus');

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Error pada destroy produk: ' . $e->getMessage(), [
                'id_produk' => $id,
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menghapus produk: ' . $e->getMessage());
        }
    }

    /**
     * Set gambar sebagai primary
     */
    public function setPrimaryImage($idProduk, $idGambar)
    {
        DB::beginTransaction();
        try {
            $gambar = ProdukGambar::where('id_produk', $idProduk)
                ->where('id_gambar', $idGambar)
                ->firstOrFail();

            // Set semua gambar produk ini menjadi non-primary
            ProdukGambar::where('id_produk', $idProduk)
                ->update(['is_primary' => false]);

            // Set gambar yang dipilih sebagai primary
            $gambar->update(['is_primary' => true]);

            DB::commit();
            return redirect()->back()->with('success', 'Gambar utama berhasil diubah');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error set primary image: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal mengubah gambar utama');
        }
    }

    /**
     * Delete gambar produk
     */
    public function deleteImage($idProduk, $idGambar)
    {
        DB::beginTransaction();
        try {
            $gambar = ProdukGambar::where('id_produk', $idProduk)
                ->where('id_gambar', $idGambar)
                ->firstOrFail();

            // Jika gambar yang dihapus adalah primary, set gambar lain sebagai primary
            if ($gambar->is_primary) {
                $otherImage = ProdukGambar::where('id_produk', $idProduk)
                    ->where('id_gambar', '!=', $idGambar)
                    ->first();
                
                if ($otherImage) {
                    $otherImage->update(['is_primary' => true]);
                }
            }

            // Delete file dari storage
            Storage::disk('public')->delete('produk/images/' . $gambar->gambar);

            $gambar->delete();

            DB::commit();
            return redirect()->back()->with('success', 'Gambar berhasil dihapus');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error delete image: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menghapus gambar');
        }
    }

    /**
     * Tambah gambar baru ke produk
     */
    public function tambahGambar(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $produk = Produk::findOrFail($id);

            $validated = $request->validate([
                'gambar' => 'required|array|min:1',
                'gambar.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:2048',
                'id_warna' => 'nullable|integer',
                'is_primary' => 'boolean'
            ]);

            $isPrimary = $request->has('is_primary') ? $request->is_primary : false;

            // Jika set sebagai primary, set semua gambar lain menjadi non-primary
            if ($isPrimary) {
                ProdukGambar::where('id_produk', $id)
                    ->update(['is_primary' => false]);
            }

            foreach ($request->file('gambar') as $gambar) {
                if ($gambar->isValid()) {
                    $gambarPath = $gambar->store('produk/images', 'public');
                    
                    ProdukGambar::create([
                        'id_produk' => $produk->id_produk,
                        'id_warna' => $request->id_warna,
                        'id_ukuran' => null,
                        'gambar' => basename($gambarPath),
                        'is_primary' => $isPrimary
                    ]);

                    // Set hanya gambar pertama sebagai primary jika requested
                    $isPrimary = false;
                }
            }

            DB::commit();
            return redirect()->back()->with('success', 'Gambar berhasil ditambahkan');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error tambah gambar: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menambahkan gambar');
        }
    }

    /**
     * Update stok produk
     */
    public function updateStok(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $produk = Produk::findOrFail($id);
            
            $validated = $request->validate([
                'stok' => 'required|array',
                'stok.*.id_ukuran' => 'required|integer',
                'stok.*.jumlah' => 'required|integer|min:0',
            ]);

            $totalStok = 0;
            
            foreach ($validated['stok'] as $stokData) {
                $ukuran = DetailUkuran::where('id_ukuran', $stokData['id_ukuran'])
                    ->where('id_produk', $id)
                    ->first();

                if ($ukuran) {
                    $ukuran->update(['stok' => $stokData['jumlah']]);
                    $totalStok += $stokData['jumlah'];
                }
            }

            $produk->update(['total_stok' => $totalStok]);

            DB::commit();
            return redirect()->back()->with('success', 'Stok berhasil diperbarui');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error update stok: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal memperbarui stok');
        }
    }

    /**
     * Get all images for product
     */
    public function getImages($id)
    {
        try {
            $produk = Produk::with(['gambarProduk'])->findOrFail($id);
            
            $images = $produk->gambarProduk->map(function($gambar) {
                return [
                    'id_gambar' => $gambar->id_gambar,
                    'url' => $gambar->gambar_url,
                    'is_primary' => $gambar->is_primary,
                    'id_warna' => $gambar->id_warna
                ];
            });

            return response()->json([
                'success' => true,
                'images' => $images
            ]);
        } catch (\Exception $e) {
            Log::error('Error get images: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data gambar'
            ], 500);
        }
    }
}