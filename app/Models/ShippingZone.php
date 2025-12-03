<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShippingZone extends Model
{
    protected $fillable = ['zone_name', 'zone_code', 'description'];

    public function cities()
    {
        return $this->hasMany(ShippingZoneCity::class);
    }
}
