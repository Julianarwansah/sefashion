<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    use HasFactory;

    protected $table = 'pembayaran';
    protected $primaryKey = 'id_pembayaran';

    protected $fillable = [
        'id_pemesanan',
        'metode_pembayaran',
        'channel',
        'external_id',
        'invoice_id',
        'payment_url',
        'tanggal_pembayaran',
        'jumlah_bayar',
        'status_pembayaran',
        'raw_response',
    ];

    protected $casts = [
        'tanggal_pembayaran' => 'datetime',
        'jumlah_bayar' => 'float',
        'raw_response' => 'array',
    ];

    /**
     * Metode pembayaran constants
     */
    const METHOD_TRANSFER = 'transfer';
    const METHOD_COD = 'cod';
    const METHOD_EWALLET = 'ewallet';
    const METHOD_VA = 'va';
    const METHOD_QRIS = 'qris';
    const METHOD_CREDIT_CARD = 'credit_card';

    /**
     * Status pembayaran constants
     */
    const STATUS_BELUM_BAYAR = 'belum_bayar';
    const STATUS_MENUNGGU = 'menunggu';
    const STATUS_SUDAH_BAYAR = 'sudah_bayar';
    const STATUS_GAGAL = 'gagal';
    const STATUS_EXPIRED = 'expired';
    const STATUS_REFUND = 'refund';

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
     * Accessor untuk tanggal pembayaran format Indonesia
     */
    public function getTanggalPembayaranFormattedAttribute()
    {
        return $this->tanggal_pembayaran?->format('d F Y H:i') ?? '-';
    }

    /**
     * Accessor untuk status pembayaran dalam bahasa Indonesia
     */
    public function getStatusPembayaranTextAttribute()
    {
        $statuses = [
            self::STATUS_BELUM_BAYAR => 'Belum Bayar',
            self::STATUS_MENUNGGU => 'Menunggu Konfirmasi',
            self::STATUS_SUDAH_BAYAR => 'Sudah Bayar',
            self::STATUS_GAGAL => 'Gagal',
            self::STATUS_EXPIRED => 'Kadaluarsa',
            self::STATUS_REFUND => 'Refund',
        ];

        return $statuses[$this->status_pembayaran] ?? $this->status_pembayaran;
    }

    /**
     * Accessor untuk metode pembayaran dalam bahasa Indonesia
     */
    public function getMetodePembayaranTextAttribute()
    {
        $methods = [
            self::METHOD_TRANSFER => 'Transfer Bank',
            self::METHOD_COD => 'Cash on Delivery',
            self::METHOD_EWALLET => 'E-Wallet',
            self::METHOD_VA => 'Virtual Account',
            self::METHOD_QRIS => 'QRIS',
            self::METHOD_CREDIT_CARD => 'Kartu Kredit',
        ];

        return $methods[$this->metode_pembayaran] ?? $this->metode_pembayaran;
    }

    /**
     * Scope untuk status tertentu
     */
    public function scopeStatus($query, $status)
    {
        return $query->where('status_pembayaran', $status);
    }

    /**
     * Scope untuk pembayaran yang berhasil
     */
    public function scopeBerhasil($query)
    {
        return $query->where('status_pembayaran', self::STATUS_SUDAH_BAYAR);
    }

    /**
     * Scope untuk pembayaran yang pending
     */
    public function scopePending($query)
    {
        return $query->whereIn('status_pembayaran', [self::STATUS_BELUM_BAYAR, self::STATUS_MENUNGGU]);
    }

    /**
     * Check jika pembayaran sudah berhasil
     */
    public function isPaid()
    {
        return $this->status_pembayaran === self::STATUS_SUDAH_BAYAR;
    }

    /**
     * Check jika pembayaran masih pending
     */
    public function isPending()
    {
        return in_array($this->status_pembayaran, [self::STATUS_BELUM_BAYAR, self::STATUS_MENUNGGU]);
    }

    /**
     * Check jika pembayaran expired
     */
    public function isExpired()
    {
        return $this->status_pembayaran === self::STATUS_EXPIRED;
    }

    /**
     * Check jika pembayaran gagal
     */
    public function isFailed()
    {
        return in_array($this->status_pembayaran, [self::STATUS_GAGAL, self::STATUS_EXPIRED]);
    }

    /**
     * Mark sebagai sudah bayar
     */
    public function markAsPaid($tanggalPembayaran = null)
    {
        $this->update([
            'status_pembayaran' => self::STATUS_SUDAH_BAYAR,
            'tanggal_pembayaran' => $tanggalPembayaran ?? now(),
        ]);

        // Update status pemesanan jika perlu
        if ($this->pemesanan && $this->pemesanan->status === Pemesanan::STATUS_PENDING) {
            $this->pemesanan->update(['status' => Pemesanan::STATUS_DIPROSES]);
        }
    }

    /**
     * Mark sebagai expired
     */
    public function markAsExpired()
    {
        $this->update([
            'status_pembayaran' => self::STATUS_EXPIRED,
        ]);
    }

    /**
     * Process webhook callback dari payment gateway
     */
    public function processWebhook($data)
    {
        $this->update([
            'raw_response' => $data,
            'status_pembayaran' => $this->determineStatusFromWebhook($data),
            'tanggal_pembayaran' => $this->extractPaymentDate($data),
        ]);

        // Simpan invoice_id jika ada
        if (isset($data['invoice_id']) && !$this->invoice_id) {
            $this->update(['invoice_id' => $data['invoice_id']]);
        }
    }

    /**
     * Determine status from webhook data
     */
    private function determineStatusFromWebhook($data)
    {
        // Logic untuk menentukan status berdasarkan data webhook
        // Sesuaikan dengan format response dari payment gateway (Xendit/Midtrans)
        if (isset($data['status'])) {
            $status = strtolower($data['status']);
            
            switch ($status) {
                case 'paid':
                case 'settlement':
                    return self::STATUS_SUDAH_BAYAR;
                case 'pending':
                    return self::STATUS_MENUNGGU;
                case 'expired':
                    return self::STATUS_EXPIRED;
                case 'failed':
                case 'deny':
                    return self::STATUS_GAGAL;
                case 'refund':
                    return self::STATUS_REFUND;
            }
        }

        return $this->status_pembayaran;
    }

    /**
     * Extract payment date from webhook data
     */
    private function extractPaymentDate($data)
    {
        if (isset($data['paid_at'])) {
            return $data['paid_at'];
        }
        if (isset($data['settlement_time'])) {
            return $data['settlement_time'];
        }
        if (isset($data['transaction_time'])) {
            return $data['transaction_time'];
        }

        return null;
    }

    /**
     * Boot method untuk handle events
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            // Generate external_id jika belum ada
            if (empty($model->external_id)) {
                $model->external_id = 'INV-' . time() . '-' . rand(1000, 9999);
            }
        });
    }
}