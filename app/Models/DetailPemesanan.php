<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPemesanan extends Model
{
    use HasFactory;

    protected $table = 'detail_pemesanan';
    protected $primaryKey = 'id_detail';

    protected $fillable = [
        'id_pemesanan',
        'id_ukuran',
        'jumlah',
        'subtotal',
    ];

    protected $casts = [
        'jumlah' => 'integer',
        'subtotal' => 'float',
    ];

    /**
     * Relationship dengan pemesanan
     */
    public function pemesanan()
    {
        return $this->belongsTo(Pemesanan::class, 'id_pemesanan', 'id_pemesanan');
    }

    /**
     * Relationship dengan detail ukuran
     */
    public function detailUkuran()
    {
        return $this->belongsTo(DetailUkuran::class, 'id_ukuran', 'id_ukuran');
    }

    /**
     * Accessor untuk format subtotal
     */
    public function getSubtotalFormattedAttribute()
    {
        return 'Rp ' . number_format((float) $this->subtotal, 0, ',', '.');
    }

    /**
     * Accessor untuk harga satuan
     */
    public function getHargaSatuanAttribute()
    {
        if ($this->jumlah > 0) {
            return $this->subtotal / $this->jumlah;
        }
        return 0;
    }

    /**
     * Accessor untuk format harga satuan
     */
    public function getHargaSatuanFormattedAttribute()
    {
        return 'Rp ' . number_format((float) $this->harga_satuan, 0, ',', '.');
    }

    /**
     * Calculate subtotal berdasarkan harga dan jumlah
     */
    public function calculateSubtotal()
    {
        if ($this->detailUkuran) {
            return $this->detailUkuran->harga * $this->jumlah;
        }
        return 0;
    }

    /**
     * Update stok produk ketika detail pemesanan dibuat/diupdate
     */
    public function updateStokProduk()
    {
        if ($this->detailUkuran) {
            $this->detailUkuran->kurangiStok($this->jumlah);
        }
    }

    /**
     * Restore stok produk ketika detail pemesanan dihapus/dibatalkan
     */
    public function restoreStokProduk()
    {
        if ($this->detailUkuran) {
            $this->detailUkuran->tambahStok($this->jumlah);
        }
    }

    /**
     * Boot method untuk handle events
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            // Auto calculate subtotal jika tidak diisi
            if (empty($model->subtotal)) {
                $model->subtotal = $model->calculateSubtotal();
            }
        });

        static::created(function ($model) {
            // Update stok ketika detail pemesanan dibuat
            $model->updateStokProduk();
            
            // Update total harga pemesanan
            $model->pemesanan->updateTotalHarga();
        });

        static::updated(function ($model) {
            // Update total harga pemesanan jika subtotal berubah
            $model->pemesanan->updateTotalHarga();
        });

        static::deleting(function ($model) {
            // Restore stok ketika detail pemesanan dihapus
            $model->restoreStokProduk();
        });

        static::deleted(function ($model) {
            // Update total harga pemesanan setelah delete
            $model->pemesanan->updateTotalHarga();
        });
    }
}