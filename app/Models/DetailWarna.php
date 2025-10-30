<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailWarna extends Model
{
    use HasFactory;

    protected $table = 'detail_warna';
    protected $primaryKey = 'id_warna';

    protected $fillable = [
        'id_produk',
        'nama_warna',
        'kode_warna',
    ];

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'id_produk', 'id_produk');
    }

    public function detailUkuran()
    {
        return $this->hasMany(DetailUkuran::class, 'id_warna', 'id_warna');
    }

    public function gambarProduk()
    {
        return $this->hasMany(ProdukGambar::class, 'id_warna', 'id_warna');
    }

    public function getKodeWarnaHexAttribute()
    {
        if ($this->kode_warna && !str_starts_with($this->kode_warna, '#')) {
            return '#' . $this->kode_warna;
        }
        return $this->kode_warna;
    }

    public function getTotalStokAttribute()
    {
        return $this->detailUkuran()->sum('stok');
    }
}
