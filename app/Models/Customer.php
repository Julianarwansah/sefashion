<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Customer extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'customers';
    protected $primaryKey = 'id_customer';

    protected $fillable = [
        'nama',
        'email',
        'password',
        'no_hp',
        'alamat',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Get the name of the unique identifier for the user.
     *
     * @return string
     */
    public function getAuthIdentifierName()
    {
        return 'id_customer';
    }

    /**
     * Get the unique identifier for the user.
     *
     * @return mixed
     */
    public function getAuthIdentifier()
    {
        return $this->{$this->getAuthIdentifierName()};
    }

    /**
     * Relationship dengan pemesanan
     */
    public function pemesanan()
    {
        return $this->hasMany(Pemesanan::class, 'id_customer', 'id_customer');
    }

    /**
     * Relationship dengan cart
     */
    public function cart()
    {
        return $this->hasMany(Cart::class, 'id_customer', 'id_customer');
    }

    /**
     * Get shipping address components for RajaOngkir
     */
    public function getShippingAddressComponents()
    {
        return [
            'city_id' => $this->getCityIdFromAddress(),
            'province_id' => $this->getProvinceIdFromAddress(),
            'city_name' => $this->getCityNameFromAddress(),
            'province_name' => $this->getProvinceNameFromAddress(),
            'full_address' => $this->alamat,
        ];
    }
    /**
     * Extract city ID from address
     * Format yang disarankan: "Jl. Alamat, Kota Jakarta Pusat [CITY:152] [PROVINCE:6]"
     */
    public function getCityIdFromAddress()
    {
        // Cari pattern [CITY:123]
        preg_match('/\[CITY:(\d+)\]/', $this->alamat, $matches);
        return $matches[1] ?? null;
    }

    /**
     * Extract province ID from address
     */
    public function getProvinceIdFromAddress()
    {
        // Cari pattern [PROVINCE:123]
        preg_match('/\[PROVINCE:(\d+)\]/', $this->alamat, $matches);
        return $matches[1] ?? null;
    }

    /**
     * Extract city name from address
     */
    public function getCityNameFromAddress()
    {
        // Cari pattern [CITY_NAME:Jakarta Pusat]
        preg_match('/\[CITY_NAME:(.+?)\]/', $this->alamat, $matches);
        return $matches[1] ?? null;
    }

    /**
     * Extract province name from address
     */
    public function getProvinceNameFromAddress()
    {
        // Cari pattern [PROVINCE_NAME:DKI Jakarta]
        preg_match('/\[PROVINCE_NAME:(.+?)\]/', $this->alamat, $matches);
        return $matches[1] ?? null;
    }

    /**
     * Format address untuk menyimpan informasi kota/provinsi
     */
    public function formatAddressWithLocation($street, $cityId, $cityName, $provinceId, $provinceName)
    {
        return "{$street} [CITY:{$cityId}] [PROVINCE:{$provinceId}] [CITY_NAME:{$cityName}] [PROVINCE_NAME:{$provinceName}]";
    }

    /**
     * Get only street address (without location tags)
     */
    public function getStreetAddressAttribute()
    {
        // Hapus semua tag [TAG:value] dari alamat
        return preg_replace('/\[[^\]]+\]/', '', $this->alamat) ?? '';
    }

    /**
     * Check if address has location data
     */
    public function hasLocationData()
    {
        return !empty($this->getCityIdFromAddress()) && !empty($this->getProvinceIdFromAddress());
    }
}