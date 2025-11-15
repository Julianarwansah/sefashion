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
     * Relationship dengan produk melalui detailUkuran
     */
    public function produk()
    {
        return $this->hasOneThrough(
            Produk::class,
            DetailUkuran::class,
            'id_ukuran', // Foreign key pada DetailUkuran
            'id_produk', // Foreign key pada Produk
            'id_ukuran', // Local key pada Cart
            'id_produk'  // Local key pada DetailUkuran
        );
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
        return $this->detailUkuran && $this->detailUkuran->stok >= $this->jumlah;
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
     * Get stok tersisa untuk item ini
     */
    public function getSisaStokAttribute()
    {
        if (!$this->detailUkuran) {
            return 0;
        }
        
        return max(0, $this->detailUkuran->stok - $this->jumlah);
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
        if ($this->detailUkuran && $this->detailUkuran->stok < $newQuantity) {
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
     * Scope untuk items dengan eager loading relationships
     */
    public function scopeWithDetails($query)
    {
        return $query->with([
            'detailUkuran',
            'detailUkuran.detailWarna',
            'detailUkuran.produk'
        ]);
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
     * Remove out of stock items from cart
     */
    public static function removeOutOfStockItems($customerId)
    {
        return static::byCustomer($customerId)
            ->whereHas('detailUkuran', function ($q) {
                $q->where('stok', '<=', 0);
            })
            ->delete();
    }

    /**
     * Add item to cart
     */
    public static function addToCart($customerId, $ukuranId, $quantity = 1)
    {
        // Validasi input
        if ($quantity <= 0) {
            return false;
        }

        // Check if item already exists in cart
        $existingCart = static::where('id_customer', $customerId)
                            ->where('id_ukuran', $ukuranId)
                            ->first();

        if ($existingCart) {
            $newQuantity = $existingCart->jumlah + $quantity;
            
            // Check stock availability
            if (!$existingCart->detailUkuran || $existingCart->detailUkuran->stok < $newQuantity) {
                return false;
            }

            return $existingCart->update(['jumlah' => $newQuantity]);
        }

        // Check stock availability untuk item baru
        $detailUkuran = DetailUkuran::find($ukuranId);
        if (!$detailUkuran || $detailUkuran->stok < $quantity) {
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
     * Get cart items with detailed information
     */
    public static function getCartWithDetails($customerId)
    {
        return static::byCustomer($customerId)
            ->withDetails()
            ->get()
            ->map(function ($item) {
                return [
                    'id_cart' => $item->id_cart,
                    'id_ukuran' => $item->id_ukuran,
                    'jumlah' => $item->jumlah,
                    'subtotal' => $item->subtotal,
                    'subtotal_formatted' => $item->subtotal_formatted,
                    'stok_tersedia' => $item->detailUkuran->stok ?? 0,
                    'stok_mencukupi' => $item->isStockAvailable(),
                    'produk' => [
                        'id_produk' => $item->detailUkuran->produk->id_produk ?? null,
                        'nama_produk' => $item->detailUkuran->produk->nama_produk ?? 'Produk tidak ditemukan',
                        'gambar' => $item->detailUkuran->produk->gambar ?? null,
                    ],
                    'warna' => [
                        'id_warna' => $item->detailUkuran->detailWarna->id_warna ?? null,
                        'nama_warna' => $item->detailUkuran->detailWarna->nama_warna ?? 'Warna tidak ditemukan',
                        'kode_warna' => $item->detailUkuran->detailWarna->kode_warna ?? null,
                    ],
                    'ukuran' => [
                        'ukuran' => $item->detailUkuran->ukuran ?? 'Ukuran tidak ditemukan',
                        'harga' => $item->detailUkuran->harga ?? 0,
                        'harga_formatted' => 'Rp ' . number_format((float) ($item->detailUkuran->harga ?? 0), 0, ',', '.'),
                        'tambahan' => $item->detailUkuran->tambahan ?? null,
                    ]
                ];
            });
    }

    /**
     * Validate all items in cart for checkout
     */
    public static function validateCartForCheckout($customerId)
    {
        $cartItems = static::byCustomer($customerId)->with('detailUkuran')->get();
        
        $errors = [];
        $validItems = [];

        foreach ($cartItems as $item) {
            if (!$item->detailUkuran) {
                $errors[] = "Item dengan ID ukuran {$item->id_ukuran} tidak ditemukan";
                continue;
            }

            if ($item->detailUkuran->stok < $item->jumlah) {
                $errors[] = "Stok untuk {$item->detailUkuran->produk->nama_produk} tidak mencukupi";
                continue;
            }

            $validItems[] = $item;
        }

        return [
            'valid' => empty($errors),
            'errors' => $errors,
            'valid_items' => $validItems,
            'total_items' => count($validItems),
            'total_price' => collect($validItems)->sum('subtotal')
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

        // Auto delete jika jumlah <= 0
        static::updated(function ($model) {
            if ($model->jumlah <= 0) {
                $model->delete();
            }
        });
    }
}