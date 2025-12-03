<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RajaOngkirService
{
    protected $apiKey;
    protected $baseUrl;

    public function __construct()
    {
        $this->apiKey = config('services.rajaongkir.key');
        // Official Komerce RajaOngkir API endpoint
        $this->baseUrl = 'https://rajaongkir.komerce.id/api/v1';
    }

    /**
     * Get Provinces
     */
    public function getProvinces()
    {
        try {
            $url = "{$this->baseUrl}/province";

            Log::info('RajaOngkir Get Provinces Request', [
                'url' => $url,
                'has_api_key' => !empty($this->apiKey)
            ]);

            $response = Http::withHeaders([
                'api_key' => $this->apiKey
            ])->get($url);

            Log::info('RajaOngkir Get Provinces Response', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return $data['rajaongkir']['results'] ?? [];
            }

            Log::error('RajaOngkir Get Provinces Error: ' . $response->body());
            return [];
        } catch (\Exception $e) {
            Log::error('RajaOngkir Get Provinces Exception: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Get Cities by Province ID
     */
    public function getCities($provinceId)
    {
        try {
            $url = "{$this->baseUrl}/city";
            $params = ['province' => $provinceId];

            Log::info('RajaOngkir Get Cities Request', [
                'url' => $url,
                'province_id' => $provinceId
            ]);

            $response = Http::withHeaders([
                'api_key' => $this->apiKey
            ])->get($url, $params);

            Log::info('RajaOngkir Get Cities Response', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return $data['rajaongkir']['results'] ?? [];
            }

            Log::error('RajaOngkir Get Cities Error: ' . $response->body());
            return [];
        } catch (\Exception $e) {
            Log::error('RajaOngkir Get Cities Exception: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Check Shipping Cost
     */
    public function checkCost($origin, $destination, $weight, $courier)
    {
        try {
            $url = "{$this->baseUrl}/cost";

            $params = [
                'origin' => $origin,
                'destination' => $destination,
                'weight' => $weight,
                'courier' => $courier
            ];

            Log::info('RajaOngkir Check Cost Request', [
                'url' => $url,
                'params' => $params
            ]);

            $response = Http::withHeaders([
                'api_key' => $this->apiKey
            ])->post($url, $params);

            Log::info('RajaOngkir Check Cost Response', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            if ($response->successful()) {
                $data = $response->json();

                // Check if response has error
                if (isset($data['rajaongkir']['status']['code']) && $data['rajaongkir']['status']['code'] != 200) {
                    $errorMessage = $data['rajaongkir']['status']['description'] ?? 'Unknown API error';
                    Log::error('RajaOngkir API Error', ['message' => $errorMessage, 'data' => $data]);
                    throw new \Exception("RajaOngkir API Error: {$errorMessage}");
                }

                // Return costs data
                $results = $data['rajaongkir']['results'] ?? [];

                if (empty($results)) {
                    Log::warning('RajaOngkir returned empty results', ['response' => $data]);
                    throw new \Exception('No shipping options available for this route');
                }

                // Extract costs from results
                $costs = [];
                foreach ($results as $result) {
                    if (isset($result['costs']) && is_array($result['costs'])) {
                        foreach ($result['costs'] as $cost) {
                            $costs[] = [
                                'service' => $cost['service'] ?? 'Unknown',
                                'description' => $cost['description'] ?? '',
                                'cost' => $cost['cost'][0]['value'] ?? 0,
                                'etd' => $cost['cost'][0]['etd'] ?? '-',
                                'note' => $cost['cost'][0]['note'] ?? ''
                            ];
                        }
                    }
                }

                if (empty($costs)) {
                    throw new \Exception('No shipping costs available');
                }

                return $costs;
            }

            // Handle non-successful HTTP response
            $errorBody = $response->body();
            Log::error('RajaOngkir Check Cost HTTP Error', [
                'status' => $response->status(),
                'body' => $errorBody
            ]);

            throw new \Exception("Failed to get shipping cost: HTTP {$response->status()}");

        } catch (\Exception $e) {
            Log::error('RajaOngkir Check Cost Exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }
}
