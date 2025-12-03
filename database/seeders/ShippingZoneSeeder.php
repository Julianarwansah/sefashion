<?php

namespace Database\Seeders;

use App\Models\ShippingZone;
use Illuminate\Database\Seeder;

class ShippingZoneSeeder extends Seeder
{
    public function run()
    {
        $zones = [
            [
                'zone_name' => 'Jawa',
                'zone_code' => 'JAVA',
                'description' => 'Pulau Jawa (DKI, Jabar, Jateng, Jatim, DIY, Banten)'
            ],
            [
                'zone_name' => 'Sumatera',
                'zone_code' => 'SUMATRA',
                'description' => 'Pulau Sumatera'
            ],
            [
                'zone_name' => 'Bali & Nusa Tenggara',
                'zone_code' => 'BALI_NUSRA',
                'description' => 'Bali, NTB, NTT'
            ],
            [
                'zone_name' => 'Kalimantan',
                'zone_code' => 'KALIMANTAN',
                'description' => 'Pulau Kalimantan'
            ],
            [
                'zone_name' => 'Sulawesi',
                'zone_code' => 'SULAWESI',
                'description' => 'Pulau Sulawesi'
            ],
            [
                'zone_name' => 'Maluku & Papua',
                'zone_code' => 'MALUKU_PAPUA',
                'description' => 'Kepulauan Maluku dan Papua'
            ],
        ];

        foreach ($zones as $zone) {
            ShippingZone::create($zone);
        }
    }
}
