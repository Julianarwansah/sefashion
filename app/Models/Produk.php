<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    use HasFactory;

    protected $table = 'produk';
    protected $primaryKey = 'id_produk';

    protected $fillable = [
        'nama_produk',
        'deskripsi',
        'kategori',
        'gambar',
        'total_stok',
    ];

    protected $casts = [
        'total_stok' => 'integer',
    ];

    // Relasi: 1 produk memiliki banyak warna
    public function detailWarna()
    {
        return $this->hasMany(DetailWarna::class, 'id_produk', 'id_produk');
    }

    // Relasi: 1 produk memiliki banyak ukuran
    public function detailUkuran()
    {
        return $this->hasMany(DetailUkuran::class, 'id_produk', 'id_produk');
    }

    // Relasi: 1 produk memiliki banyak gambar
    public function gambarProduk()
    {
        return $this->hasMany(ProdukGambar::class, 'id_produk', 'id_produk');
    }

    // Scope produk tersedia
    public function scopeTersedia($query)
    {
        return $query->where('total_stok', '>', 0);
    }

    // Scope kategori
    public function scopeKategori($query, $kategori)
    {
        return $query->where('kategori', $kategori);
    }

    // Accessor gambar URL
    public function getGambarUrlAttribute()
    {
        // Cek gambar utama di field 'gambar'
        if (isset($this->attributes['gambar']) && $this->attributes['gambar']) {
            $path = storage_path('app/public/produk/' . $this->attributes['gambar']);
            if (file_exists($path)) {
                return asset('storage/produk/' . $this->attributes['gambar']);
            }
        }

        // Jika tidak ada gambar utama, coba ambil dari produk_gambar yang is_primary
        $primaryImage = $this->gambarProduk()->where('is_primary', 1)->first();
        if ($primaryImage && $primaryImage->gambar) {
            $path = storage_path('app/public/produk/images/' . $primaryImage->gambar);
            if (file_exists($path)) {
                return asset('storage/produk/images/' . $primaryImage->gambar);
            }
        }

        // Jika masih tidak ada, ambil gambar pertama dari produk_gambar
        $firstImage = $this->gambarProduk()->first();
        if ($firstImage && $firstImage->gambar) {
            $path = storage_path('app/public/produk/images/' . $firstImage->gambar);
            if (file_exists($path)) {
                return asset('storage/produk/images/' . $firstImage->gambar);
            }
        }

        // Return placeholder jika gambar tidak ada
        return 'https://via.placeholder.com/400x500/CCCCCC/666666?text=No+Image';
    }

    // Update total stok berdasarkan detail ukuran
    public function updateTotalStok()
    {
        $totalStok = $this->detailUkuran()->sum('stok');
        $this->update(['total_stok' => $totalStok]);
    }
}
