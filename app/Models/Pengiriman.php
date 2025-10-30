<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengiriman extends Model
{
    use HasFactory;

    protected $table = 'pengiriman';
    protected $primaryKey = 'id_pengiriman';

    protected $fillable = [
        'id_pemesanan',
        'nama_penerima',
        'no_hp_penerima',
        'alamat_tujuan',
        'ekspedisi',
        'layanan',
        'biaya_ongkir',
        'no_resi',
        'status_pengiriman',
        'tanggal_dikirim',
        'tanggal_diterima',
    ];

    protected $casts = [
        'biaya_ongkir' => 'float',
        'tanggal_dikirim' => 'datetime',
        'tanggal_diterima' => 'datetime',
    ];

    /**
     * Status pengiriman constants
     */
    const STATUS_MENUNGGU = 'menunggu';
    const STATUS_DIKIRIM = 'dikirim';
    const STATUS_DITERIMA = 'diterima';
    const STATUS_GAGAL = 'gagal';

    /**
     * Relationship dengan pemesanan
     */
    public function pemesanan()
    {
        return $this->belongsTo(Pemesanan::class, 'id_pemesanan', 'id_pemesanan');
    }

    /**
     * Accessor untuk format biaya ongkir
     */
    public function getBiayaOngkirFormattedAttribute()
    {
        return 'Rp ' . number_format((float) $this->biaya_ongkir, 0, ',', '.');
    }

    /**
     * Accessor untuk status pengiriman dalam bahasa Indonesia
     */
    public function getStatusPengirimanTextAttribute()
    {
        $statuses = [
            self::STATUS_MENUNGGU => 'Menunggu Pengiriman',
            self::STATUS_DIKIRIM => 'Sedang Dikirim',
            self::STATUS_DITERIMA => 'Sudah Diterima',
            self::STATUS_GAGAL => 'Gagal Dikirim',
        ];

        return $statuses[$this->status_pengiriman] ?? $this->status_pengiriman;
    }

    /**
     * Accessor untuk tanggal dikirim format Indonesia
     */
    public function getTanggalDikirimFormattedAttribute()
    {
        return $this->tanggal_dikirim?->format('d F Y H:i') ?? '-';
    }

    /**
     * Accessor untuk tanggal diterima format Indonesia
     */
    public function getTanggalDiterimaFormattedAttribute()
    {
        return $this->tanggal_diterima?->format('d F Y H:i') ?? '-';
    }

    /**
     * Accessor untuk estimasi pengiriman (jika ada no resi)
     */
    public function getEstimasiPengirimanAttribute()
    {
        if (!$this->tanggal_dikirim) {
            return null;
        }

        // Estimasi 1-3 hari untuk pengiriman reguler
        $estimasiHari = 3;
        return $this->tanggal_dikirim->copy()->addDays($estimasiHari);
    }

    /**
     * Accessor untuk format estimasi pengiriman
     */
    public function getEstimasiPengirimanFormattedAttribute()
    {
        return $this->estimasi_pengiriman?->format('d F Y') ?? '-';
    }

    /**
     * Check jika pengiriman sudah dikirim
     */
    public function isShipped()
    {
        return $this->status_pengiriman === self::STATUS_DIKIRIM;
    }

    /**
     * Check jika pengiriman sudah diterima
     */
    public function isDelivered()
    {
        return $this->status_pengiriman === self::STATUS_DITERIMA;
    }

    /**
     * Check jika pengiriman gagal
     */
    public function isFailed()
    {
        return $this->status_pengiriman === self::STATUS_GAGAL;
    }

    /**
     * Check jika pengiriman sedang dalam proses
     */
    public function isInTransit()
    {
        return in_array($this->status_pengiriman, [self::STATUS_MENUNGGU, self::STATUS_DIKIRIM]);
    }

    /**
     * Check jika memiliki nomor resi
     */
    public function hasTrackingNumber()
    {
        return !empty($this->no_resi);
    }

    /**
     * Mark sebagai dikirim
     */
    public function markAsShipped($noResi = null, $tanggalDikirim = null)
    {
        $updateData = [
            'status_pengiriman' => self::STATUS_DIKIRIM,
            'tanggal_dikirim' => $tanggalDikirim ?? now(),
        ];

        if ($noResi) {
            $updateData['no_resi'] = $noResi;
        }

        $this->update($updateData);

        // Update status pemesanan jika perlu
        if ($this->pemesanan && $this->pemesanan->status === Pemesanan::STATUS_DIPROSES) {
            $this->pemesanan->update(['status' => Pemesanan::STATUS_DIKIRIM]);
        }
    }

    /**
     * Mark sebagai diterima
     */
    public function markAsDelivered($tanggalDiterima = null)
    {
        $this->update([
            'status_pengiriman' => self::STATUS_DITERIMA,
            'tanggal_diterima' => $tanggalDiterima ?? now(),
        ]);

        // Update status pemesanan jika perlu
        if ($this->pemesanan && $this->pemesanan->status === Pemesanan::STATUS_DIKIRIM) {
            $this->pemesanan->update(['status' => Pemesanan::STATUS_SELESAI]);
        }
    }

    /**
     * Mark sebagai gagal
     */
    public function markAsFailed()
    {
        $this->update([
            'status_pengiriman' => self::STATUS_GAGAL,
        ]);

        // Update status pemesanan jika perlu
        if ($this->pemesanan) {
            $this->pemesanan->update(['status' => Pemesanan::STATUS_BATAL]);
        }
    }

    /**
     * Get tracking URL berdasarkan ekspedisi
     */
    public function getTrackingUrlAttribute()
    {
        if (!$this->no_resi) {
            return null;
        }

        $trackingUrls = [
            'jne' => "https://www.jne.co.id/id/tracking/trace?awb={$this->no_resi}",
            'tiki' => "https://www.tiki.id/id/tracking?waybill={$this->no_resi}",
            'pos' => "https://www.posindonesia.co.id/id/tracking?nomor={$this->no_resi}",
            'jnt' => "https://www.jet.co.id/track?no={$this->no_resi}",
            'sicepat' => "https://www.sicepat.com/check-awb?awb={$this->no_resi}",
            'anteraja' => "https://anteraja.id/tracking?lc={$this->no_resi}",
            'ninja' => "https://www.ninjaxpress.co/track?no={$this->no_resi}",
            'grab' => "https://grab.com/id/ship/track/?tracking_id={$this->no_resi}",
            'gojek' => "https://www.gojek.com/ship/track/?tracking_id={$this->no_resi}",
        ];

        $ekspedisi = strtolower($this->ekspedisi);
        
        // Cari pattern matching untuk ekspedisi
        foreach ($trackingUrls as $key => $url) {
            if (str_contains($ekspedisi, $key)) {
                return $url;
            }
        }

        return null;
    }

    /**
     * Scope untuk status tertentu
     */
    public function scopeStatus($query, $status)
    {
        return $query->where('status_pengiriman', $status);
    }

    /**
     * Scope untuk pengiriman yang sedang dalam proses
     */
    public function scopeInTransit($query)
    {
        return $query->whereIn('status_pengiriman', [self::STATUS_MENUNGGU, self::STATUS_DIKIRIM]);
    }

    /**
     * Scope untuk pengiriman yang sudah selesai
     */
    public function scopeCompleted($query)
    {
        return $query->whereIn('status_pengiriman', [self::STATUS_DITERIMA, self::STATUS_GAGAL]);
    }

    /**
     * Scope untuk pengiriman berdasarkan nomor resi
     */
    public function scopeByResi($query, $noResi)
    {
        return $query->where('no_resi', $noResi);
    }

    /**
     * Calculate total harga termasuk ongkir
     */
    public function getTotalHargaDenganOngkirAttribute()
    {
        if ($this->pemesanan) {
            return $this->pemesanan->total_harga + $this->biaya_ongkir;
        }
        return $this->biaya_ongkir;
    }

    /**
     * Accessor untuk format total harga dengan ongkir
     */
    public function getTotalHargaDenganOngkirFormattedAttribute()
    {
        return 'Rp ' . number_format((float) $this->total_harga_dengan_ongkir, 0, ',', '.');
    }

    /**
     * Check jika pengiriman terlambat
     */
    public function isLate()
    {
        if (!$this->estimasi_pengiriman || $this->isDelivered() || $this->isFailed()) {
            return false;
        }

        return now()->gt($this->estimasi_pengiriman);
    }

    /**
     * Get days late (jika terlambat)
     */
    public function getDaysLateAttribute()
    {
        if (!$this->isLate()) {
            return 0;
        }

        return now()->diffInDays($this->estimasi_pengiriman);
    }

    /**
     * Boot method untuk handle events
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            // Set default values jika perlu
        });

        static::updated(function ($model) {
            // Handle status changes
            $originalStatus = $model->getOriginal('status_pengiriman');
            $newStatus = $model->status_pengiriman;

            // Jika status berubah menjadi dikirim dan tanggal_dikirim belum diisi
            if ($newStatus === self::STATUS_DIKIRIM && !$model->tanggal_dikirim) {
                $model->update(['tanggal_dikirim' => now()]);
            }

            // Jika status berubah menjadi diterima dan tanggal_diterima belum diisi
            if ($newStatus === self::STATUS_DITERIMA && !$model->tanggal_diterima) {
                $model->update(['tanggal_diterima' => now()]);
            }
        });
    }
}