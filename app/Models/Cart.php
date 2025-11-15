<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $table = 'cart';
    protected $primaryKey = 'id_cart';

    // Nonaktifkan timestamps otomatis
    public $timestamps = false;

    protected $fillable = [
        'id_customer',
        'id_ukuran', 
        'jumlah',
        'tanggal_ditambahkan'
    ];

    protected $casts = [
        'tanggal_ditambahkan' => 'datetime',
    ];

    /**
     * Relationship dengan detail ukuran
     */
    public function detailUkuran()
    {
        return $this->belongsTo(DetailUkuran::class, 'id_ukuran', 'id_ukuran');
    }

    /**
     * Relationship dengan produk melalui detailUkuran
     */
    public function produk()
    {
        return $this->hasOneThrough(
            Produk::class,
            DetailUkuran::class,
            'id_ukuran',
            'id_produk',
            'id_ukuran',
            'id_produk'
        );
    }

    /**
     * Accessor untuk subtotal item
     */
    public function getSubtotalAttribute()
    {
        if ($this->detailUkuran && $this->detailUkuran->harga) {
            return $this->detailUkuran->harga * $this->jumlah;
        }
        return 0;
    }

    /**
     * Accessor untuk format subtotal
     */
    public function getSubtotalFormattedAttribute()
    {
        return 'Rp ' . number_format((float) $this->subtotal, 0, ',', '.');
    }

    /**
     * Check jika stok masih mencukupi
     */
    public function isStockAvailable()
    {
        return $this->detailUkuran && $this->detailUkuran->stok >= $this->jumlah;
    }

    /**
     * Update jumlah item di cart
     */
    public function updateQuantity($newQuantity)
    {
        if ($newQuantity <= 0) {
            return $this->delete();
        }

        // Check stok tersedia
        if ($this->detailUkuran && $this->detailUkuran->stok < $newQuantity) {
            return false;
        }

        // Update hanya kolom jumlah tanpa updated_at
        return $this->update(['jumlah' => $newQuantity]);
    }

    /**
     * Increment quantity
     */
    public function incrementQuantity($amount = 1)
    {
        $newQuantity = $this->jumlah + $amount;
        
        // Check stok tersedia
        if ($this->detailUkuran && $this->detailUkuran->stok < $newQuantity) {
            return false;
        }

        return $this->update(['jumlah' => $newQuantity]);
    }

    /**
     * Decrement quantity
     */
    public function decrementQuantity($amount = 1)
    {
        $newQuantity = max(1, $this->jumlah - $amount);
        return $this->update(['jumlah' => $newQuantity]);
    }

    /**
     * Scope untuk cart items customer tertentu
     */
    public function scopeByCustomer($query, $customerId)
    {
        return $query->where('id_customer', $customerId);
    }

    /**
     * Scope untuk items dengan stok tersedia
     */
    public function scopeAvailableStock($query)
    {
        return $query->whereHas('detailUkuran', function ($q) {
            $q->where('stok', '>', 0);
        });
    }

    /**
     * Get total items in cart for customer
     */
    public static function getTotalItems($customerId)
    {
        return static::byCustomer($customerId)->sum('jumlah');
    }

    /**
     * Get total price in cart for customer
     */
    public static function getTotalPrice($customerId)
    {
        $cartItems = static::byCustomer($customerId)
            ->with('detailUkuran')
            ->get();

        return $cartItems->sum(function ($item) {
            return $item->subtotal;
        });
    }

    /**
     * Get formatted total price in cart for customer
     */
    public static function getTotalPriceFormatted($customerId)
    {
        $total = static::getTotalPrice($customerId);
        return 'Rp ' . number_format((float) $total, 0, ',', '.');
    }

    /**
     * Clear cart for customer
     */
    public static function clearCart($customerId)
    {
        return static::byCustomer($customerId)->delete();
    }

    /**
     * Add item to cart
     */
    public static function addToCart($customerId, $ukuranId, $quantity = 1)
    {
        // Check if item already exists in cart
        $existingCart = static::where('id_customer', $customerId)
                            ->where('id_ukuran', $ukuranId)
                            ->first();

        if ($existingCart) {
            return $existingCart->incrementQuantity($quantity);
        }

        // Check stock availability
        $detailUkuran = DetailUkuran::find($ukuranId);
        if (!$detailUkuran || $detailUkuran->stok < $quantity) {
            return false;
        }

        // Create new cart item - tanpa created_at dan updated_at
        return static::create([
            'id_customer' => $customerId,
            'id_ukuran' => $ukuranId,
            'jumlah' => $quantity,
            'tanggal_ditambahkan' => now(),
        ]);
    }
}