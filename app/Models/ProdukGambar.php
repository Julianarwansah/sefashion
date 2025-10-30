<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProdukGambar extends Model
{
    use HasFactory;

    protected $table = 'produk_gambar';
    protected $primaryKey = 'id_gambar';

    protected $fillable = [
        'id_produk',
        'id_warna',
        'id_ukuran',
        'gambar',
        'is_primary',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
    ];

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'id_produk', 'id_produk');
    }

    public function warna()
    {
        return $this->belongsTo(DetailWarna::class, 'id_warna', 'id_warna');
    }

    public function ukuran()
    {
        return $this->belongsTo(DetailUkuran::class, 'id_ukuran', 'id_ukuran');
    }

    public function scopePrimary($query)
    {
        return $query->where('is_primary', true);
    }

    public function getGambarUrlAttribute()
    {
        return $this->gambar
            ? asset('storage/produk/images/' . $this->gambar)
            : asset('images/default-product.jpg');
    }

    public function setAsPrimary()
    {
        self::where('id_produk', $this->id_produk)
            ->where('id_gambar', '!=', $this->id_gambar)
            ->update(['is_primary' => false]);

        $this->update(['is_primary' => true]);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (!self::where('id_produk', $model->id_produk)->exists()) {
                $model->is_primary = true;
            }
        });
    }
}
