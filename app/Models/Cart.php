<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

class Cart extends Model
{
    use HasFactory;

    protected $table = 'cart';
    protected $primaryKey = 'id_cart';
    public $timestamps = false;

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

    protected $appends = [
        'subtotal',
        'subtotal_formatted',
        'is_stock_available'
    ];

    /**
     * Relationship dengan customer
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'id_customer', 'id_customer');
    }

    /**
     * Relationship dengan detail ukuran dengan eager loading
     */
    public function detailUkuran(): BelongsTo
    {
        return $this->belongsTo(DetailUkuran::class, 'id_ukuran', 'id_ukuran')
            ->with(['detailWarna.produk', 'detailWarna.produkGambars']);
    }

    /**
     * Accessor untuk subtotal item
     */
    public function getSubtotalAttribute(): float
    {
        if ($this->relationLoaded('detailUkuran') && $this->detailUkuran) {
            return $this->detailUkuran->harga * $this->jumlah;
        }
        return 0;
    }

    /**
     * Accessor untuk format subtotal
     */
    public function getSubtotalFormattedAttribute(): string
    {
        return 'Rp ' . number_format($this->subtotal, 0, ',', '.');
    }

    /**
     * Accessor untuk cek ketersediaan stok
     */
    public function getIsStockAvailableAttribute(): bool
    {
        if ($this->relationLoaded('detailUkuran') && $this->detailUkuran) {
            return $this->detailUkuran->stok >= $this->jumlah;
        }
        return false;
    }

    /**
     * Update jumlah item di cart dengan transaction
     */
    public function updateQuantity(int $newQuantity): bool
    {
        if ($newQuantity <= 0) {
            return $this->delete();
        }

        return DB::transaction(function () use ($newQuantity) {
            // Reload detailUkuran untuk mendapatkan stok terbaru
            $this->load('detailUkuran');
            
            if (!$this->detailUkuran || $this->detailUkuran->stok < $newQuantity) {
                return false;
            }

            $this->update(['jumlah' => $newQuantity]);
            return true;
        });
    }

    /**
     * Increment quantity dengan transaction
     */
    public function incrementQuantity(int $amount = 1): bool
    {
        return DB::transaction(function () use ($amount) {
            $newQuantity = $this->jumlah + $amount;
            
            $this->load('detailUkuran');
            if (!$this->detailUkuran || $this->detailUkuran->stok < $newQuantity) {
                return false;
            }

            $this->update(['jumlah' => $newQuantity]);
            return true;
        });
    }

    /**
     * Decrement quantity
     */
    public function decrementQuantity(int $amount = 1): bool
    {
        $newQuantity = max(1, $this->jumlah - $amount);
        $this->update(['jumlah' => $newQuantity]);
        return true;
    }

    /**
     * Scope untuk cart items customer tertentu
     */
    public function scopeByCustomer($query, int $customerId)
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
     * Scope dengan data produk lengkap
     */
    public function scopeWithProductDetails($query)
    {
        return $query->with([
            'detailUkuran.detailWarna.produk',
            'detailUkuran.detailWarna.produkGambars' => function ($query) {
                $query->where('is_primary', 1);
            }
        ]);
    }

    /**
     * Get total items in cart for customer
     */
    public static function getTotalItems(int $customerId): int
    {
        return static::byCustomer($customerId)->sum('jumlah');
    }

    /**
     * Get total price in cart for customer
     */
    public static function getTotalPrice(int $customerId): float
    {
        $cartItems = static::byCustomer($customerId)
            ->withProductDetails()
            ->get();
        
        return $cartItems->sum('subtotal');
    }

    /**
     * Get formatted total price in cart for customer
     */
    public static function getTotalPriceFormatted(int $customerId): string
    {
        $total = static::getTotalPrice($customerId);
        return 'Rp ' . number_format($total, 0, ',', '.');
    }

    /**
     * Clear cart for customer
     */
    public static function clearCart(int $customerId): bool
    {
        return static::byCustomer($customerId)->delete();
    }

    /**
     * Add item to cart dengan transaction dan validation
     */
    public static function addToCart(int $customerId, int $ukuranId, int $quantity = 1): self
    {
        return DB::transaction(function () use ($customerId, $ukuranId, $quantity) {
            // Check if item already exists in cart
            $existingCart = static::where('id_customer', $customerId)
                                ->where('id_ukuran', $ukuranId)
                                ->first();

            if ($existingCart) {
                $existingCart->incrementQuantity($quantity);
                return $existingCart->fresh(['detailUkuran.detailWarna.produk']);
            }

            // Check stock availability
            $detailUkuran = DetailUkuran::with('detailWarna.produk')->find($ukuranId);
            
            if (!$detailUkuran) {
                throw new \Exception('Varian produk tidak ditemukan');
            }

            if ($detailUkuran->stok < $quantity) {
                throw new \Exception('Stok produk tidak mencukupi');
            }

            // Create new cart item
            $cart = static::create([
                'id_customer' => $customerId,
                'id_ukuran' => $ukuranId,
                'jumlah' => $quantity,
                'tanggal_ditambahkan' => now(),
            ]);

            return $cart->load(['detailUkuran.detailWarna.produk']);
        });
    }

    /**
     * Validate all cart items for checkout
     */
    public static function validateCartForCheckout(int $customerId): array
    {
        $cartItems = static::byCustomer($customerId)
            ->withProductDetails()
            ->get();

        $errors = [];
        $validItems = [];

        foreach ($cartItems as $cartItem) {
            if (!$cartItem->is_stock_available) {
                $errors[] = [
                    'product' => $cartItem->detailUkuran->detailWarna->produk->nama_produk,
                    'variant' => $cartItem->detailUkuran->detailWarna->nama_warna . ' - ' . $cartItem->detailUkuran->ukuran,
                    'requested' => $cartItem->jumlah,
                    'available' => $cartItem->detailUkuran->stok,
                    'message' => 'Stok tidak mencukupi'
                ];
            } else {
                $validItems[] = $cartItem;
            }
        }

        return [
            'valid_items' => $validItems,
            'errors' => $errors,
            'is_valid' => empty($errors)
        ];
    }

    /**
     * Get cart summary for customer
     */
    public static function getCartSummary(int $customerId): array
    {
        $cartItems = static::byCustomer($customerId)
            ->withProductDetails()
            ->get();

        $totalQuantity = $cartItems->sum('jumlah');
        $totalPrice = $cartItems->sum('subtotal');

        return [
            'items' => $cartItems,
            'summary' => [
                'total_items' => $cartItems->count(),
                'total_quantity' => $totalQuantity,
                'total_price' => $totalPrice,
                'formatted_total_price' => 'Rp ' . number_format($totalPrice, 0, ',', '.')
            ]
        ];
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
}