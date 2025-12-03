<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BinderbyteService
{
    protected $apiKey;
    protected $baseUrl;

    public function __construct()
    {
        $this->apiKey = config('services.binderbyte.key');
        $this->baseUrl = 'https://api.binderbyte.com';
    }

    /**
     * Get Provinces
     */
    public function getProvinces()
    {
        try {
            $url = "{$this->baseUrl}/wilayah/provinsi";
            $params = ['api_key' => $this->apiKey];

            Log::info('Binderbyte Get Provinces Request', [
                'url' => $url,
                'has_api_key' => !empty($this->apiKey)
            ]);

            $response = Http::get($url, $params);

            Log::info('Binderbyte Get Provinces Response', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            if ($response->successful()) {
                $data = $response->json();
                Log::info('Binderbyte Parsed Data', ['data' => $data]);
                return $data['value'] ?? [];
            }

            Log::error('Binderbyte Get Provinces Error: ' . $response->body());
            return [];
        } catch (\Exception $e) {
            Log::error('Binderbyte Get Provinces Exception: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Get Cities by Province ID
     */
    public function getCities($provinceId)
    {
        try {
            $response = Http::get("{$this->baseUrl}/wilayah/kabupaten", [
                'api_key' => $this->apiKey,
                'id_provinsi' => $provinceId
            ]);

            if ($response->successful()) {
                return $response->json()['value'] ?? [];
            }

            Log::error('Binderbyte Get Cities Error: ' . $response->body());
            return [];
        } catch (\Exception $e) {
            Log::error('Binderbyte Get Cities Exception: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Get Districts (Kecamatan) by City ID
     */
    public function getDistricts($cityId)
    {
        try {
            $response = Http::get("{$this->baseUrl}/wilayah/kecamatan", [
                'api_key' => $this->apiKey,
                'id_kabupaten' => $cityId
            ]);

            if ($response->successful()) {
                return $response->json()['value'] ?? [];
            }

            Log::error('Binderbyte Get Districts Error: ' . $response->body());
            return [];
        } catch (\Exception $e) {
            Log::error('Binderbyte Get Districts Exception: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Check Shipping Cost
     */
    public function checkCost($origin, $destination, $weight, $courier)
    {
        try {
            $url = "{$this->baseUrl}/v1/cost";

            $params = [
                'api_key' => $this->apiKey,
                'origin' => $origin,
                'destination' => $destination,
                'weight' => $weight,
                'courier' => $courier
            ];

            Log::info('Binderbyte Check Cost Request', [
                'url' => $url,
                'params' => $params
            ]);

            $response = Http::timeout(30)->get($url, $params);

            Log::info('Binderbyte Check Cost Response', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            if ($response->successful()) {
                $data = $response->json();

                // Check if response has error
                if (isset($data['error']) && $data['error']) {
                    $errorMessage = $data['message'] ?? 'Unknown API error';
                    Log::error('Binderbyte API Error', ['message' => $errorMessage, 'data' => $data]);
                    throw new \Exception("Binderbyte API Error: {$errorMessage}");
                }

                // Return costs data
                $costs = $data['data']['costs'] ?? [];

                if (empty($costs)) {
                    Log::warning('Binderbyte returned empty costs', ['response' => $data]);
                    throw new \Exception('No shipping options available for this route');
                }

                return $costs;
            }

            // Handle non-successful HTTP response
            $errorBody = $response->body();
            Log::error('Binderbyte Check Cost HTTP Error', [
                'status' => $response->status(),
                'body' => $errorBody
            ]);

            throw new \Exception("Failed to get shipping cost: HTTP {$response->status()}");

        } catch (\Exception $e) {
            Log::error('Binderbyte Check Cost Exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }
}
