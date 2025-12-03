<?php

namespace Database\Seeders;

use App\Models\ShippingRate;
use App\Models\ShippingZone;
use Illuminate\Database\Seeder;

class ShippingRateSeeder extends Seeder
{
    public function run()
    {
        $zones = ShippingZone::all();
        $couriers = ['JNE', 'TIKI', 'POS', 'SICEPAT', 'JNT'];
        $services = [
            'REG' => 'Regular',
            'YES' => 'Express',
            'OKE' => 'Economy'
        ];

        // Base rates matrix (Origin -> Destination)
        // Prices in thousands (x1000)
        $baseRates = [
            'JAVA' => [
                'JAVA' => 15,
                'SUMATRA' => 25,
                'BALI_NUSRA' => 30,
                'KALIMANTAN' => 35,
                'SULAWESI' => 40,
                'MALUKU_PAPUA' => 60
            ],
            'SUMATRA' => [
                'JAVA' => 25,
                'SUMATRA' => 20,
                'BALI_NUSRA' => 40,
                'KALIMANTAN' => 45,
                'SULAWESI' => 50,
                'MALUKU_PAPUA' => 70
            ],
            // ... simplify for other origins, assume symmetric or similar
        ];

        foreach ($couriers as $courier) {
            foreach ($zones as $origin) {
                foreach ($zones as $destination) {

                    // Determine base rate
                    $originCode = $origin->zone_code;
                    $destCode = $destination->zone_code;

                    // Default rate if not in matrix
                    $rate = 50;
                    if (isset($baseRates[$originCode][$destCode])) {
                        $rate = $baseRates[$originCode][$destCode];
                    } elseif ($originCode == $destCode) {
                        $rate = 20; // Default same zone
                    }

                    // Create rates for each service
                    foreach ($services as $type => $name) {
                        $multiplier = 1.0;
                        $etd = '2-3 hari';

                        if ($type == 'YES') {
                            $multiplier = 1.5;
                            $etd = '1-2 hari';
                        } elseif ($type == 'OKE') {
                            $multiplier = 0.8;
                            $etd = '4-7 hari';
                        }

                        $finalRate = $rate * $multiplier * 1000;

                        ShippingRate::create([
                            'courier' => $courier,
                            'service_type' => $type,
                            'service_name' => $name,
                            'origin_zone_id' => $origin->id,
                            'destination_zone_id' => $destination->id,
                            'min_weight' => 0,
                            'max_weight' => 100000, // Up to 100kg
                            'base_rate' => $finalRate,
                            'per_kg_rate' => $finalRate * 0.8, // Per kg is slightly cheaper
                            'estimated_days' => $etd
                        ]);
                    }
                }
            }
        }
    }
}
