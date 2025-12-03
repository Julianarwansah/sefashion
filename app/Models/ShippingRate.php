<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShippingRate extends Model
{
    protected $fillable = [
        'courier',
        'service_type',
        'service_name',
        'origin_zone_id',
        'destination_zone_id',
        'min_weight',
        'max_weight',
        'base_rate',
        'per_kg_rate',
        'estimated_days'
    ];

    public function originZone()
    {
        return $this->belongsTo(ShippingZone::class, 'origin_zone_id');
    }

    public function destinationZone()
    {
        return $this->belongsTo(ShippingZone::class, 'destination_zone_id');
    }
}
