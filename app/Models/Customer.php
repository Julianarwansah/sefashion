<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Customer extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The table associated with the model.
     */
    protected $table = 'customers';

    /**
     * The primary key associated with the table.
     */
    protected $primaryKey = 'id_customer';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'nama',
        'email',
        'password',
        'no_hp',
        'alamat',
        'province_id',
        'city_id',
        'province_name',
        'city_name',
        'kode_pos'
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    /**
     * Get the name of the unique identifier for the user.
     */
    public function getAuthIdentifierName()
    {
        return 'id_customer';
    }

    /**
     * Relationship dengan pemesanan
     */
    public function pemesanan()
    {
        return $this->hasMany(Pemesanan::class, 'id_customer', 'id_customer');
    }

    /**
     * Accessor untuk alamat lengkap
     */
    public function getAlamatLengkapAttribute()
    {
        $alamat = $this->alamat;
        
        if ($this->city_name) {
            $alamat .= ', ' . $this->city_name;
        }
        
        if ($this->province_name) {
            $alamat .= ', ' . $this->province_name;
        }
        
        if ($this->kode_pos) {
            $alamat .= ' - ' . $this->kode_pos;
        }
        
        return $alamat;
    }

    /**
     * Check jika customer memiliki alamat lengkap
     */
    public function hasCompleteAddress()
    {
        return !empty($this->alamat) && 
               !empty($this->city_id) && 
               !empty($this->province_id) &&
               !empty($this->city_name) &&
               !empty($this->province_name);
    }

    /**
     * Scope untuk customer dengan alamat lengkap
     */
    public function scopeWithCompleteAddress($query)
    {
        return $query->whereNotNull('alamat')
                    ->whereNotNull('city_id')
                    ->whereNotNull('province_id')
                    ->whereNotNull('city_name')
                    ->whereNotNull('province_name')
                    ->where('alamat', '!=', '')
                    ->where('city_name', '!=', '')
                    ->where('province_name', '!=', '');
    }
}