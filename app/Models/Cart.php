<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $table = 'cart';
    protected $primaryKey = 'id_cart';

    protected $fillable = [
        'id_customer',
        'id_ukuran',
        'jumlah',
        'tanggal_ditambahkan',
    ];

    protected $casts = [
        'jumlah' => 'integer',
        'tanggal_ditambahkan' => 'datetime',
    ];

    /**
     * Relationship dengan customer
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'id_customer', 'id_customer');
    }

    /**
     * Relationship dengan detail ukuran
     */
    public function detailUkuran()
    {
        return $this->belongsTo(DetailUkuran::class, 'id_ukuran', 'id_ukuran');
    }

    /**
     * Accessor untuk subtotal item
     */
    public function getSubtotalAttribute()
    {
        if ($this->detailUkuran) {
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
        return $this->detailUkuran && $this->detailUkuran->stokMencukupi($this->jumlah);
    }

    /**
     * Check jika item sudah melebihi stok
     */
    public function isExceedingStock()
    {
        if (!$this->detailUkuran) {
            return false;
        }
        
        return $this->jumlah > $this->detailUkuran->stok;
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
        if ($this->detailUkuran && !$this->detailUkuran->stokMencukupi($newQuantity)) {
            return false;
        }

        $this->update(['jumlah' => $newQuantity]);
        return true;
    }

    /**
     * Increment quantity
     */
    public function incrementQuantity($amount = 1)
    {
        $newQuantity = $this->jumlah + $amount;
        
        // Check stok tersedia
        if ($this->detailUkuran && !$this->detailUkuran->stokMencukupi($newQuantity)) {
            return false;
        }

        $this->update(['jumlah' => $newQuantity]);
        return true;
    }

    /**
     * Decrement quantity
     */
    public function decrementQuantity($amount = 1)
    {
        $newQuantity = max(1, $this->jumlah - $amount);
        $this->update(['jumlah' => $newQuantity]);
        return true;
    }

    /**
     * Scope untuk cart items customer tertentu
     */
    public function scopeByCustomer($query, $customerId)
    {
        return $query->where('id_customer', $customerId);
    }

    /**
     * Scope untuk items yang stoknya tersedia
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
        $cartItems = static::byCustomer($customerId)->with('detailUkuran')->get();
        
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
        if (!$detailUkuran || !$detailUkuran->stokMencukupi($quantity)) {
            return false;
        }

        // Create new cart item
        return static::create([
            'id_customer' => $customerId,
            'id_ukuran' => $ukuranId,
            'jumlah' => $quantity,
            'tanggal_ditambahkan' => now(),
        ]);
    }

    /**
     * Boot method untuk handle events
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->tanggal_ditambahkan)) {
                $model->tanggal_ditambahkan = now();
            }
        });
    }

    /**
     * Check if item with same size already exists in cart
     */
    public static function itemExists($customerId, $ukuranId)
    {
        return static::where('id_customer', $customerId)
                    ->where('id_ukuran', $ukuranId)
                    ->exists();
    }

    /**
     * Get cart item by size
     */
    public static function getItemBySize($customerId, $ukuranId)
    {
        return static::where('id_customer', $customerId)
                    ->where('id_ukuran', $ukuranId)
                    ->first();
    }

    /**
     * Scope untuk items dengan stok yang cukup
     */
    public function scopeWithAvailableStock($query)
    {
        return $query->whereHas('detailUkuran', function ($q) {
            $q->where('stok', '>', 0);
        });
    }

    /**
     * Get cart summary for customer
     */
    public static function getCartSummary($customerId)
    {
        $cartItems = static::byCustomer($customerId)
            ->with(['detailUkuran.produk', 'detailUkuran.detailWarna'])
            ->get();

        $totalItems = $cartItems->sum('jumlah');
        $totalPrice = $cartItems->sum(function ($item) {
            return $item->subtotal;
        });

        return [
            'items' => $cartItems,
            'total_items' => $totalItems,
            'total_price' => $totalPrice,
            'total_price_formatted' => 'Rp ' . number_format($totalPrice, 0, ',', '.')
        ];
    }
}