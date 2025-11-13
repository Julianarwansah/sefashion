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

    // Alias untuk backward compatibility
    public function gambar()
    {
        return $this->gambarProduk();
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
        return $this->gambar
            ? asset('storage/produk/' . $this->gambar)
            : asset('images/default-product.jpg');
    }

    // Update total stok berdasarkan detail ukuran
    public function updateTotalStok()
    {
        $totalStok = $this->detailUkuran()->sum('stok');
        $this->update(['total_stok' => $totalStok]);
    }
}
