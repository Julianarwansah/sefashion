<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class RajaOngkirService
{
    protected $apiKey;
    protected $baseUrl;
    protected $package;

    public function __construct()
    {
        $this->apiKey = config('services.rajaongkir.key');
        $this->package = config('services.rajaongkir.package', 'starter');
        
        if ($this->package === 'pro') {
            $this->baseUrl = 'https://pro.rajaongkir.com/api/';
        } else {
            $this->baseUrl = 'https://api.rajaongkir.com/'. $this->package .'/';
        }
    }


    /**
     * Make API request to RajaOngkir dengan timeout lebih pendek
     */
    private function makeRequest($endpoint, $params = [], $method = 'GET')
    {
        try {
            $url = $this->baseUrl . $endpoint;
            
            $response = Http::withHeaders([
                'key' => $this->apiKey,
                'Content-Type' => 'application/x-www-form-urlencoded'
            ])->timeout(10) // Timeout diperpendek dari 30 ke 10 detik
              ->retry(2, 100); // Retry 2x dengan delay 100ms

            if ($method === 'POST') {
                $response = $response->asForm()->post($url, $params);
            } else {
                $response = $response->get($url, $params);
            }

            // Check if response is successful
            if (!$response->successful()) {
                Log::warning('RajaOngkir API Response Failed', [
                    'endpoint' => $endpoint,
                    'status' => $response->status(),
                    'response' => $response->body()
                ]);
                return null;
            }

            $data = $response->json();
            
            if (!isset($data['rajaongkir']['status']['code']) || $data['rajaongkir']['status']['code'] !== 200) {
                Log::error('RajaOngkir API Error', [
                    'endpoint' => $endpoint,
                    'response' => $data
                ]);
                return null;
            }

            return $data['rajaongkir'];

        } catch (\Exception $e) {
            Log::error('RajaOngkir API Exception: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get provinces dengan cache yang lebih lama
     */
    public function getProvinces()
    {
        return Cache::remember('rajaongkir_provinces', now()->addDays(30), function () { // Cache 30 hari
            $result = $this->makeRequest('province');
            return $result['results'] ?? [];
        });
    }

    /**
     * Get cities dengan cache yang lebih lama
     */
    public function getCities($provinceId = null)
    {
        $cacheKey = 'rajaongkir_cities' . ($provinceId ? '_' . $provinceId : '');

        return Cache::remember($cacheKey, now()->addDays(30), function () use ($provinceId) { // Cache 30 hari
            $params = [];
            if ($provinceId) {
                $params['province'] = $provinceId;
            }

            $result = $this->makeRequest('city', $params);
            return $result['results'] ?? [];
        });
    }

    /**
     * Get city by ID dengan cache
     */
    public function getCity($cityId)
    {
        $cacheKey = 'rajaongkir_city_' . $cityId;

        return Cache::remember($cacheKey, now()->addDays(30), function () use ($cityId) {
            $result = $this->makeRequest('city', ['id' => $cityId]);
            return $result['results'] ?? null;
        });
    }

    /**
     * Get shipping cost dengan timeout khusus
     */
    public function getShippingCost($origin, $destination, $weight, $courier)
    {
        $cacheKey = "shipping_cost_{$origin}_{$destination}_{$weight}_{$courier}";

        return Cache::remember($cacheKey, now()->addHours(2), function () use ($origin, $destination, $weight, $courier) {
            // Pre-check untuk parameter yang invalid
            if (empty($origin) || empty($destination) || empty($weight) || empty($courier)) {
                return [];
            }

            $result = $this->makeRequest('cost', [
                'origin' => $origin,
                'destination' => $destination,
                'weight' => $weight,
                'courier' => $courier
            ], 'POST');

            if (isset($result['results'][0]['costs'])) {
                return $result['results'][0]['costs'];
            }

            return [];
        });
    }

    /**
     * Get international shipping cost (untuk package pro)
     */
    public function getInternationalCost($origin, $destination, $weight, $courier)
    {
        if ($this->package !== 'pro') {
            return [];
        }

        return Cache::remember("international_cost_{$origin}_{$destination}_{$weight}_{$courier}", 
            now()->addHours(1), 
            function () use ($origin, $destination, $weight, $courier) {
                $result = $this->makeRequest('v2/internationalCost', [
                    'origin' => $origin,
                    'destination' => $destination,
                    'weight' => $weight,
                    'courier' => $courier
                ], 'POST');

                return $result['results'][0]['costs'] ?? [];
            }
        );
    }

    /**
     * Get all available couriers
     */
    public function getAvailableCouriers()
    {
        $couriers = [
            'jne' => 'JNE',
            'pos' => 'POS Indonesia',
            'tiki' => 'TIKI',
        ];

        // Untuk package basic dan pro, tambahkan courier lainnya
        if (in_array($this->package, ['basic', 'pro'])) {
            $additionalCouriers = [
                'rpx' => 'RPX',
                'esl' => 'Eka Sari Lorena',
                'pcp' => 'PCP',
                'pandu' => 'Pandu',
                'wahana' => 'Wahana',
                'sicepat' => 'SiCepat',
                'jnt' => 'J&T',
                'pahala' => 'Pahala',
                'cahaya' => 'Cahaya',
                'sat' => 'Sat',
                'jet' => 'JET',
                'indah' => 'Indah Logistic',
                'dse' => '21 Express',
                'slis' => 'Solusi Express',
                'first' => 'First Logistics',
                'ncs' => 'Nusantara Card Semesta',
                'star' => 'Star Cargo',
            ];
            $couriers = array_merge($couriers, $additionalCouriers);
        }

        return $couriers;
    }

    /**
     * Check API status dengan timeout sangat singkat
     */
    public function checkApiStatus()
    {
        try {
            // Gunakan endpoint yang ringan (province) dengan timeout sangat pendek
            $response = Http::withHeaders([
                'key' => $this->apiKey
            ])->timeout(3) // Timeout 3 detik untuk status check
              ->get($this->baseUrl . 'province');

            return $response->successful();
        } catch (\Exception $e) {
            Log::warning('RajaOngkir API Status Check Failed: ' . $e->getMessage());
            return false;
        }
    }
}