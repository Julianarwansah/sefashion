<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pemesanan extends Model
{
    use HasFactory;

    protected $table = 'pemesanan';
    protected $primaryKey = 'id_pemesanan';

    protected $fillable = [
        'id_customer',
        'tanggal_pemesanan',
        'total_harga',
        'status',
    ];

    protected $casts = [
        'tanggal_pemesanan' => 'datetime',
        'total_harga' => 'float',
    ];

    /**
     * Status constants
     */
    const STATUS_PENDING = 'pending';
    const STATUS_DIPROSES = 'diproses';
    const STATUS_DIKIRIM = 'dikirim';
    const STATUS_SELESAI = 'selesai';
    const STATUS_BATAL = 'batal';

    /**
     * Relationship dengan customer
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'id_customer', 'id_customer');
    }

    /**
     * Relationship dengan detail pemesanan
     */
    public function detailPemesanan()
    {
        return $this->hasMany(DetailPemesanan::class, 'id_pemesanan', 'id_pemesanan');
    }

    /**
     * Relationship dengan pembayaran
     */
    public function pembayaran()
    {
        return $this->hasOne(Pembayaran::class, 'id_pemesanan', 'id_pemesanan');
    }

    /**
     * Relationship dengan pengiriman
     */
    public function pengiriman()
    {
        return $this->hasOne(Pengiriman::class, 'id_pemesanan', 'id_pemesanan');
    }

    /**
     * Accessor untuk format total harga
     */
    public function getTotalHargaFormattedAttribute()
    {
        return 'Rp ' . number_format((float) $this->total_harga, 0, ',', '.');
    }

    /**
     * Accessor untuk tanggal pemesanan format Indonesia
     */
    public function getTanggalPemesananFormattedAttribute()
    {
        return $this->tanggal_pemesanan->format('d F Y H:i');
    }

    /**
     * Scope untuk status tertentu
     */
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope untuk pemesanan pending
     */
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    /**
     * Scope untuk pemesanan aktif (bukan batal/selesai)
     */
    public function scopeAktif($query)
    {
        return $query->whereNotIn('status', [self::STATUS_BATAL, self::STATUS_SELESAI]);
    }

    /**
     * Update total harga berdasarkan detail pemesanan
     */
    public function updateTotalHarga()
    {
        $total = $this->detailPemesanan()->sum('subtotal');
        $this->update(['total_harga' => $total]);
        return $total;
    }

    /**
     * Check jika pemesanan bisa dibatalkan
     */
    public function canBeCancelled()
    {
        return in_array($this->status, [self::STATUS_PENDING, self::STATUS_DIPROSES]);
    }

    /**
     * Check jika pemesanan sudah dibayar
     */
    public function isPaid()
    {
        return $this->pembayaran && $this->pembayaran->status_pembayaran === 'sudah_bayar';
    }

    /**
     * Get items count
     */
    public function getTotalItemsAttribute()
    {
        return $this->detailPemesanan()->sum('jumlah');
    }

    /**
     * Boot method untuk handle events
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->tanggal_pemesanan)) {
                $model->tanggal_pemesanan = now();
            }
        });
    }
}