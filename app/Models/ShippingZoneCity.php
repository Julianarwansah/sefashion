<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShippingZoneCity extends Model
{
    protected $fillable = [
        'shipping_zone_id',
        'city_id',
        'city_name',
        'province_id',
        'province_name'
    ];

    public function zone()
    {
        return $this->belongsTo(ShippingZone::class, 'shipping_zone_id');
    }
}
