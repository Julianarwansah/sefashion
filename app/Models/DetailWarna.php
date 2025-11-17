<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailWarna extends Model
{
    use HasFactory;

    protected $table = 'detail_warna';
    protected $primaryKey = 'id_warna';
    public $timestamps = false;

    protected $fillable = [
        'id_produk',
        'nama_warna',
        'kode_warna',
    ];

    /**
     * Relasi ke produk (Many to One)
     */
    public function produk()
    {
        return $this->belongsTo(Produk::class, 'id_produk', 'id_produk');
    }

    /**
     * Relasi ke DetailUkuran (One to Many)
     * 1 warna punya banyak ukuran
     */
    public function detailUkuran()
    {
        return $this->hasMany(DetailUkuran::class, 'id_warna', 'id_warna');
    }

    /**
     * Relasi ke gambar produk (One to Many)
     */
    public function gambarProduk()
    {
        return $this->hasMany(ProdukGambar::class, 'id_warna', 'id_warna');
    }

    /**
     * Menghasilkan format HEX untuk warna
     */
    public function getKodeWarnaHexAttribute()
    {
        if ($this->kode_warna && !str_starts_with($this->kode_warna, '#')) {
            return '#' . $this->kode_warna;
        }

        return $this->kode_warna;
    }

    /**
     * Total stok berdasarkan semua ukuran dalam warna ini
     */
    public function getTotalStokAttribute()
    {
        return $this->detailUkuran()->sum('stok');
    }
}
