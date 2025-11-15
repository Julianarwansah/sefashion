<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailUkuran extends Model
{
    use HasFactory;

    protected $table = 'detail_ukuran';
    protected $primaryKey = 'id_ukuran';
    public $timestamps = false;

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

    /**
     * Relasi ke produk
     */
    public function produk()
    {
        return $this->belongsTo(Produk::class, 'id_produk', 'id_produk');
    }

    /**
     * Relasi ke warna
     * 1 ukuran → 1 warna
     */
    public function detailWarna()
    {
        return $this->belongsTo(DetailWarna::class, 'id_warna', 'id_warna');
    }

    /**
     * Gambar berdasarkan ukuran
     */
    public function gambarProduk()
    {
        return $this->hasMany(ProdukGambar::class, 'id_ukuran', 'id_ukuran');
    }

    /**
     * Relasi detail pemesanan
     */
    public function detailPemesanan()
    {
        return $this->hasMany(DetailPemesanan::class, 'id_ukuran', 'id_ukuran');
    }

    /**
     * Relasi ke Cart
     */
    public function cart()
    {
        return $this->hasMany(Cart::class, 'id_ukuran', 'id_ukuran');
    }

    /**
     * Scope stok tersedia
     */
    public function scopeTersedia($query)
    {
        return $query->where('stok', '>', 0);
    }

    /**
     * Harga format Rp
     */
    public function getHargaFormattedAttribute()
    {
        return 'Rp ' . number_format((float) $this->harga, 0, ',', '.');
    }

    /**
     * Cek stok
     */
    public function stokMencukupi($jumlah)
    {
        return $this->stok >= $jumlah;
    }

    /**
     * Kurangi stok
     */
    public function kurangiStok($jumlah)
    {
        $this->decrement('stok', $jumlah);

        if ($this->produk) {
            $this->produk->updateTotalStok();
        }
    }

    /**
     * Tambah stok
     */
    public function tambahStok($jumlah)
    {
        $this->increment('stok', $jumlah);

        if ($this->produk) {
            $this->produk->updateTotalStok();
        }
    }
}
