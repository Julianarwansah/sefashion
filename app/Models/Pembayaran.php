<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Pembayaran extends Model
{
    use HasFactory;

    protected $table = 'pembayaran';
    protected $primaryKey = 'id_pembayaran';

    protected $fillable = [
        'id_pemesanan',
        'metode_pembayaran',
        'channel',
        'jumlah_bayar',
        'status_pembayaran',
        'external_id',
        'xendit_id',
        'xendit_external_id',
        'xendit_payment_url',
        'xendit_expiry_date',
        'xendit_merchant_name',
        'xendit_account_number',
    ];

    protected $casts = [
        'jumlah_bayar' => 'decimal:2',
        'xendit_expiry_date' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Status constants
     */
    const STATUS_MENUNGGU = 'menunggu';
    const STATUS_BELUM_BAYAR = 'belum_bayar';
    const STATUS_SUDAH_BAYAR = 'sudah_bayar';
    const STATUS_KADALUARSA = 'kadaluarsa';
    const STATUS_GAGAL = 'gagal';

    /**
     * Metode pembayaran constants
     */
    const METHOD_VA = 'va';
    const METHOD_EWALLET = 'ewallet';
    const METHOD_RETAIL = 'retail';
    const METHOD_COD = 'cod';

    /**
     * Relationship dengan pemesanan
     */
    public function pemesanan()
    {
        return $this->belongsTo(Pemesanan::class, 'id_pemesanan', 'id_pemesanan');
    }

    /**
     * Accessor untuk format jumlah bayar
     */
    public function getJumlahBayarFormattedAttribute()
    {
        return 'Rp ' . number_format((float) $this->jumlah_bayar, 0, ',', '.');
    }

    /**
     * Accessor untuk nomor akun yang diformat
     */
    public function getAccountNumberDisplayAttribute()
    {
        if (!$this->xendit_account_number) {
            return '-';
        }

        // Format nomor VA agar lebih mudah dibaca
        return preg_replace('/(\d{4})(?=\d)/', '$1 ', $this->xendit_account_number);
    }

    /**
     * Check jika pembayaran sudah expired
     */
    public function isExpired()
    {
        if (!$this->xendit_expiry_date) {
            return false;
        }
        return now()->greaterThan($this->xendit_expiry_date);
    }

    /**
     * Check jika pembayaran masih pending
     */
    public function isPending()
    {
        return $this->status_pembayaran === self::STATUS_MENUNGGU;
    }

    /**
     * Check jika pembayaran sudah berhasil
     */
    public function isPaid()
    {
        return $this->status_pembayaran === self::STATUS_SUDAH_BAYAR;
    }

    /**
     * Check jika pembayaran COD
     */
    public function isCOD()
    {
        return $this->metode_pembayaran === self::METHOD_COD;
    }

    /**
     * Update status pembayaran
     */
    public function updateStatus($status)
    {
        $this->update(['status_pembayaran' => $status]);
        
        // Jika status sudah bayar, update pemesanan
        if ($status === self::STATUS_SUDAH_BAYAR && $this->pemesanan) {
            $this->pemesanan->update(['status' => Pemesanan::STATUS_DIPROSES]);
        }
    }

    /**
     * Get payment URL untuk redirect
     */
    public function getPaymentUrlAttribute()
    {
        return $this->xendit_payment_url;
    }

    /**
     * Get nama metode pembayaran yang lebih friendly
     */
    public function getMetodePembayaranDisplayAttribute()
    {
        $displayNames = [
            self::METHOD_VA => 'Virtual Account',
            self::METHOD_EWALLET => 'E-Wallet',
            self::METHOD_RETAIL => 'Retail Outlet',
            self::METHOD_COD => 'Cash on Delivery',
        ];

        return $displayNames[$this->metode_pembayaran] ?? $this->metode_pembayaran;
    }

    /**
     * Get nama channel yang lebih friendly
     */
    public function getChannelDisplayAttribute()
    {
        $channelNames = [
            'BCA' => 'BCA',
            'BRI' => 'BRI',
            'BNI' => 'BNI',
            'MANDIRI' => 'Mandiri',
            'PERMATA' => 'Permata',
            'DANA' => 'DANA',
            'OVO' => 'OVO',
            'SHOPEEPAY' => 'ShopeePay',
            'LINKAJA' => 'LinkAja',
            'ALFAMART' => 'Alfamart',
            'INDOMARET' => 'Indomaret',
            'COD' => 'Cash on Delivery',
        ];

        return $channelNames[$this->channel] ?? $this->channel;
    }

    /**
     * Boot method untuk generate external_id otomatis
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->external_id)) {
                $model->external_id = 'PAY-' . now()->format('YmdHis') . '-' . Str::random(6);
            }
        });
    }

    /**
     * Scope queries untuk status tertentu
     */
    public function scopePending($query)
    {
        return $query->where('status_pembayaran', self::STATUS_MENUNGGU);
    }

    public function scopePaid($query)
    {
        return $query->where('status_pembayaran', self::STATUS_SUDAH_BAYAR);
    }

    public function scopeExpired($query)
    {
        return $query->where('status_pembayaran', self::STATUS_KADALUARSA);
    }

    public function scopeFailed($query)
    {
        return $query->where('status_pembayaran', self::STATUS_GAGAL);
    }

    /**
     * Scope untuk pembayaran yang belum expired
     */
    public function scopeNotExpired($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('xendit_expiry_date')
              ->orWhere('xendit_expiry_date', '>', now());
        });
    }
}