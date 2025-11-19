<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class RajaOngkirService
{
    protected $apiKey;
    protected $baseUrl;
    protected $accountType;
    protected $origin;

    public function __construct()
    {
        $this->apiKey = env('RAJAONGKIR_API_KEY');
        $this->accountType = env('RAJAONGKIR_ACCOUNT_TYPE', 'starter');
        $this->origin = [
            'city_id' => env('STORE_CITY_ID', 152),
            'province_id' => env('STORE_PROVINCE_ID', 6),
            'city' => env('STORE_CITY', 'Jakarta Selatan'),
            'province' => env('STORE_PROVINCE', 'DKI Jakarta'),
        ];

        $this->baseUrl = $this->accountType === 'pro' 
            ? 'https://pro.rajaongkir.com/api/'
            : 'https://api.rajaongkir.com/' . $this->accountType . '/';
    }

    /**
     * Make API request to RajaOngkir
     */
    private function makeRequest($endpoint, $data = [], $method = 'GET')
    {
        try {
            $url = $this->baseUrl . $endpoint;
            
            $response = Http::withHeaders([
                'key' => $this->apiKey,
                'Content-Type' => 'application/x-www-form-urlencoded',
            ])->timeout(30)->{$method}($url, $data);

            if ($response->successful()) {
                $result = $response->json();
                if (isset($result['rajaongkir']['status']['code']) && $result['rajaongkir']['status']['code'] === 200) {
                    return $result['rajaongkir']['results'] ?? $result['rajaongkir'];
                }
            }

            Log::error('RajaOngkir API Error: ' . $response->body());
            return null;

        } catch (\Exception $e) {
            Log::error('RajaOngkir Service Exception: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get all provinces
     */
    public function getProvinces()
    {
        return Cache::remember('rajaongkir_provinces', 86400, function () { // 1 day cache
            return $this->makeRequest('province');
        });
    }

    /**
     * Get cities by province
     */
    public function getCities($provinceId = null)
    {
        $cacheKey = 'rajaongkir_cities' . ($provinceId ? '_' . $provinceId : '');
        
        return Cache::remember($cacheKey, 86400, function () use ($provinceId) {
            $data = [];
            if ($provinceId) {
                $data['province'] = $provinceId;
            }
            return $this->makeRequest('city', $data);
        });
    }

    /**
     * Get shipping cost
     */
    public function getCost($destination, $weight, $courier = 'jne')
    {
        $data = [
            'origin' => $this->origin['city_id'],
            'destination' => $destination,
            'weight' => $weight,
            'courier' => $courier,
        ];

        return $this->makeRequest('cost', $data, 'POST');
    }

    /**
     * Get shipping cost for multiple couriers
     */
    public function getMultipleCosts($destination, $weight, $couriers = ['jne', 'tiki', 'pos'])
    {
        $results = [];
        
        foreach ($couriers as $courier) {
            $cost = $this->getCost($destination, $weight, $courier);
            if ($cost && isset($cost['costs']) && !empty($cost['costs'])) {
                $results[$courier] = $this->formatCosts($cost);
            }
        }

        return $results;
    }

    /**
     * Format costs response
     */
    private function formatCosts($costData)
    {
        $formatted = [];
        
        foreach ($costData['costs'] as $cost) {
            $service = $cost['service'];
            $etd = $cost['cost'][0]['etd'] ?? '';
            $price = $cost['cost'][0]['value'] ?? 0;

            $formatted[] = [
                'service' => $service,
                'description' => $cost['description'] ?? '',
                'cost' => $price,
                'etd' => $this->cleanEtd($etd),
                'formatted_cost' => 'Rp ' . number_format($price, 0, ',', '.'),
                'formatted_etd' => $this->formatEtd($etd),
            ];
        }

        return $formatted;
    }

    /**
     * Clean ETD string
     */
    private function cleanEtd($etd)
    {
        if (strpos($etd, 'HARI') !== false) {
            return str_replace(' HARI', '', $etd);
        }
        return $etd;
    }

    /**
     * Format ETD for display
     */
    private function formatEtd($etd)
    {
        $cleanedEtd = $this->cleanEtd($etd);
        return $cleanedEtd ? "{$cleanedEtd} hari" : '1-3 hari';
    }

    /**
     * Get store origin info
     */
    public function getOrigin()
    {
        return $this->origin;
    }
}