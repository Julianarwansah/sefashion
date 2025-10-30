<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailUkuran extends Model
{
    use HasFactory;

    protected $table = 'detail_ukuran';
    protected $primaryKey = 'id_ukuran';

    protected $fillable = [
        'id_produk',
        'id_warna',
        'ukuran',
        'harga',
        'stok',
        'tambahan',
    ];

    protected $casts = [
        'harga' => 'decimal:2',
        'stok' => 'integer',
    ];

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'id_produk', 'id_produk');
    }

    public function warna()
    {
        return $this->belongsTo(DetailWarna::class, 'id_warna', 'id_warna');
    }

    public function gambarProduk()
    {
        return $this->hasMany(ProdukGambar::class, 'id_ukuran', 'id_ukuran');
    }

    public function detailPemesanan()
    {
        return $this->hasMany(DetailPemesanan::class, 'id_ukuran', 'id_ukuran');
    }

    public function cart()
    {
        return $this->hasMany(Cart::class, 'id_ukuran', 'id_ukuran');
    }

    public function scopeTersedia($query)
    {
        return $query->where('stok', '>', 0);
    }

    public function getHargaFormattedAttribute()
    {
        return 'Rp ' . number_format((float) $this->harga, 0, ',', '.');
    }

    public function stokMencukupi($jumlah)
    {
        return $this->stok >= $jumlah;
    }

    public function kurangiStok($jumlah)
    {
        $this->decrement('stok', $jumlah);
        $this->produk->updateTotalStok();
    }

    public function tambahStok($jumlah)
    {
        $this->increment('stok', $jumlah);
        $this->produk->updateTotalStok();
    }
}
