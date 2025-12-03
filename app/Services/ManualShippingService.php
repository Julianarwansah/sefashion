<?php

namespace App\Services;

use App\Models\ShippingRate;
use Illuminate\Support\Facades\Log;

class ManualShippingService
{
    /**
     * Calculate shipping cost based on manual rates
     * Simple version: Map province ID directly to zone
     */
    public function calculateShipping($originCityId, $destinationCityId, $weight, $courier)
    {
        try {
            // Simple province-to-zone mapping (no database lookup needed!)
            $zoneMap = [
                // Java (Zone 1)
                '31' => 1,
                '32' => 1,
                '33' => 1,
                '34' => 1,
                '35' => 1,
                '36' => 1,
                // Sumatra (Zone 2)
                '11' => 2,
                '12' => 2,
                '13' => 2,
                '14' => 2,
                '15' => 2,
                '16' => 2,
                '17' => 2,
                '18' => 2,
                '19' => 2,
                '21' => 2,
                // Bali & Nusra (Zone 3)
                '51' => 3,
                '52' => 3,
                '53' => 3,
                // Kalimantan (Zone 4)
                '61' => 4,
                '62' => 4,
                '63' => 4,
                '64' => 4,
                '65' => 4,
                // Sulawesi (Zone 5)
                '71' => 5,
                '72' => 5,
                '73' => 5,
                '74' => 5,
                '75' => 5,
                '76' => 5,
                // Maluku & Papua (Zone 6)
                '81' => 6,
                '82' => 6,
                '91' => 6,
                '92' => 6,
                '93' => 6,
                '94' => 6,
                '95' => 6,
            ];

            // Extract province ID from city ID (format: "12.04" -> "12")
            $originProvinceId = explode('.', $originCityId)[0];
            $destProvinceId = explode('.', $destinationCityId)[0];

            // Get zone IDs from province
            $originZoneId = $zoneMap[$originProvinceId] ?? 1; // Default to Java
            $destZoneId = $zoneMap[$destProvinceId] ?? 1;

            Log::info('ManualShippingService: Zone mapping', [
                'origin_city' => $originCityId,
                'dest_city' => $destinationCityId,
                'origin_zone' => $originZoneId,
                'dest_zone' => $destZoneId
            ]);

            // Get shipping rates
            $rates = ShippingRate::where('courier', strtoupper($courier))
                ->where('origin_zone_id', $originZoneId)
                ->where('destination_zone_id', $destZoneId)
                ->where('min_weight', '<=', $weight)
                ->where('max_weight', '>=', $weight)
                ->get();

            if ($rates->isEmpty()) {
                Log::warning('ManualShippingService: No rates found', [
                    'courier' => $courier,
                    'origin_zone' => $originZoneId,
                    'dest_zone' => $destZoneId,
                    'weight' => $weight
                ]);
                return [];
            }

            $results = [];
            foreach ($rates as $rate) {
                // Calculate cost: base_rate + (additional_kg * per_kg_rate)
                $weightInKg = ceil($weight / 1000);
                $additionalKg = max(0, $weightInKg - 1);

                $cost = $rate->base_rate + ($additionalKg * $rate->per_kg_rate);

                $results[] = [
                    'service' => $rate->service_name,
                    'description' => $rate->service_type,
                    'cost' => (int) $cost,
                    'etd' => $rate->estimated_days
                ];
            }

            return $results;

        } catch (\Exception $e) {
            Log::error('ManualShippingService Error: ' . $e->getMessage());
            return [];
        }
    }
}
