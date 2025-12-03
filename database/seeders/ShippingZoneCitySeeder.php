<?php

namespace Database\Seeders;

use App\Models\ShippingZone;
use App\Models\ShippingZoneCity;
use Illuminate\Database\Seeder;

class ShippingZoneCitySeeder extends Seeder
{
    public function run()
    {
        // Get Zone IDs
        $java = ShippingZone::where('zone_code', 'JAVA')->first()->id;
        $sumatra = ShippingZone::where('zone_code', 'SUMATRA')->first()->id;
        $baliNusra = ShippingZone::where('zone_code', 'BALI_NUSRA')->first()->id;
        $kalimantan = ShippingZone::where('zone_code', 'KALIMANTAN')->first()->id;
        $sulawesi = ShippingZone::where('zone_code', 'SULAWESI')->first()->id;
        $malukuPapua = ShippingZone::where('zone_code', 'MALUKU_PAPUA')->first()->id;

        // Sample Cities Mapping (BinderByte IDs)
        // BinderByte uses format: province_id.city_id (e.g., "31.01" for Jakarta Pusat)

        $cities = [
            // JAVA - DKI Jakarta (Province ID: 31)
            ['city_id' => '31.01', 'city_name' => 'Jakarta Pusat', 'province_id' => '31', 'province_name' => 'DKI JAKARTA', 'zone_id' => $java],
            ['city_id' => '31.02', 'city_name' => 'Jakarta Utara', 'province_id' => '31', 'province_name' => 'DKI JAKARTA', 'zone_id' => $java],
            ['city_id' => '31.03', 'city_name' => 'Jakarta Barat', 'province_id' => '31', 'province_name' => 'DKI JAKARTA', 'zone_id' => $java],
            ['city_id' => '31.04', 'city_name' => 'Jakarta Selatan', 'province_id' => '31', 'province_name' => 'DKI JAKARTA', 'zone_id' => $java],
            ['city_id' => '31.05', 'city_name' => 'Jakarta Timur', 'province_id' => '31', 'province_name' => 'DKI JAKARTA', 'zone_id' => $java],

            // Jawa Barat (Province ID: 32)
            ['city_id' => '32.01', 'city_name' => 'Bandung', 'province_id' => '32', 'province_name' => 'JAWA BARAT', 'zone_id' => $java],
            ['city_id' => '32.02', 'city_name' => 'Bekasi', 'province_id' => '32', 'province_name' => 'JAWA BARAT', 'zone_id' => $java],
            ['city_id' => '32.03', 'city_name' => 'Bogor', 'province_id' => '32', 'province_name' => 'JAWA BARAT', 'zone_id' => $java],

            // Jawa Tengah (Province ID: 33)
            ['city_id' => '33.01', 'city_name' => 'Semarang', 'province_id' => '33', 'province_name' => 'JAWA TENGAH', 'zone_id' => $java],

            // Jawa Timur (Province ID: 35)
            ['city_id' => '35.01', 'city_name' => 'Surabaya', 'province_id' => '35', 'province_name' => 'JAWA TIMUR', 'zone_id' => $java],

            // Banten (Province ID: 36)
            ['city_id' => '36.01', 'city_name' => 'Tangerang', 'province_id' => '36', 'province_name' => 'BANTEN', 'zone_id' => $java],

            // SUMATRA - Sumatera Utara (Province ID: 12)
            ['city_id' => '12.01', 'city_name' => 'Medan', 'province_id' => '12', 'province_name' => 'SUMATERA UTARA', 'zone_id' => $sumatra],
            ['city_id' => '12.02', 'city_name' => 'Binjai', 'province_id' => '12', 'province_name' => 'SUMATERA UTARA', 'zone_id' => $sumatra],
            ['city_id' => '12.03', 'city_name' => 'Pematang Siantar', 'province_id' => '12', 'province_name' => 'SUMATERA UTARA', 'zone_id' => $sumatra],
            ['city_id' => '12.04', 'city_name' => 'Kab. Tapanuli Tengah', 'province_id' => '12', 'province_name' => 'SUMATERA UTARA', 'zone_id' => $sumatra],
            ['city_id' => '12.05', 'city_name' => 'Kab. Tapanuli Utara', 'province_id' => '12', 'province_name' => 'SUMATERA UTARA', 'zone_id' => $sumatra],
            ['city_id' => '12.06', 'city_name' => 'Kab. Tapanuli Selatan', 'province_id' => '12', 'province_name' => 'SUMATERA UTARA', 'zone_id' => $sumatra],
            ['city_id' => '12.07', 'city_name' => 'Kab. Asahan', 'province_id' => '12', 'province_name' => 'SUMATERA UTARA', 'zone_id' => $sumatra],
            ['city_id' => '12.08', 'city_name' => 'Kab. Deli Serdang', 'province_id' => '12', 'province_name' => 'SUMATERA UTARA', 'zone_id' => $sumatra],
            ['city_id' => '12.09', 'city_name' => 'Kab. Simalungun', 'province_id' => '12', 'province_name' => 'SUMATERA UTARA', 'zone_id' => $sumatra],
            ['city_id' => '12.10', 'city_name' => 'Kab. Labuhanbatu', 'province_id' => '12', 'province_name' => 'SUMATERA UTARA', 'zone_id' => $sumatra],

            // Sumatera Barat (Province ID: 13)
            ['city_id' => '13.01', 'city_name' => 'Padang', 'province_id' => '13', 'province_name' => 'SUMATERA BARAT', 'zone_id' => $sumatra],

            // Sumatera Selatan (Province ID: 16)
            ['city_id' => '16.01', 'city_name' => 'Palembang', 'province_id' => '16', 'province_name' => 'SUMATERA SELATAN', 'zone_id' => $sumatra],

            // Lampung (Province ID: 18)
            ['city_id' => '18.01', 'city_name' => 'Bandar Lampung', 'province_id' => '18', 'province_name' => 'LAMPUNG', 'zone_id' => $sumatra],

            // BALI & NUSRA - Bali (Province ID: 51)
            ['city_id' => '51.01', 'city_name' => 'Denpasar', 'province_id' => '51', 'province_name' => 'BALI', 'zone_id' => $baliNusra],

            // KALIMANTAN - Kalimantan Barat (Province ID: 61)
            ['city_id' => '61.01', 'city_name' => 'Pontianak', 'province_id' => '61', 'province_name' => 'KALIMANTAN BARAT', 'zone_id' => $kalimantan],

            // Kalimantan Timur (Province ID: 64)
            ['city_id' => '64.01', 'city_name' => 'Balikpapan', 'province_id' => '64', 'province_name' => 'KALIMANTAN TIMUR', 'zone_id' => $kalimantan],

            // SULAWESI - Sulawesi Selatan (Province ID: 73)
            ['city_id' => '73.01', 'city_name' => 'Makassar', 'province_id' => '73', 'province_name' => 'SULAWESI SELATAN', 'zone_id' => $sulawesi],

            // MALUKU & PAPUA - Papua (Province ID: 91)
            ['city_id' => '91.01', 'city_name' => 'Jayapura', 'province_id' => '91', 'province_name' => 'PAPUA', 'zone_id' => $malukuPapua],
        ];

        foreach ($cities as $city) {
            ShippingZoneCity::create([
                'shipping_zone_id' => $city['zone_id'],
                'city_id' => $city['city_id'],
                'city_name' => $city['city_name'],
                'province_id' => $city['province_id'],
                'province_name' => $city['province_name'],
            ]);
        }
    }
}
