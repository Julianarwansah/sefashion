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
}