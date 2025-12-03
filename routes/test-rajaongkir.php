<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

Route::get('/test-rajaongkir', function () {
    $apiKey = config('services.rajaongkir.key');
    $baseUrl = 'https://collaborator.komerce.id/v1';

    Log::info('Testing RajaOngkir API', [
        'api_key' => substr($apiKey, 0, 10) . '...',
        'base_url' => $baseUrl
    ]);

    // Test 1: Get Provinces
    try {
        $url = "{$baseUrl}/province";

        $response = Http::withHeaders([
            'Authorization' => "Bearer {$apiKey}",
            'Accept' => 'application/json',
        ])->get($url);

        Log::info('Province Response', [
            'status' => $response->status(),
            'body' => $response->body()
        ]);

        $data = $response->json();

        return response()->json([
            'test' => 'Get Provinces',
            'status' => $response->status(),
            'success' => $response->successful(),
            'data' => $data,
            'provinces_count' => isset($data['rajaongkir']['results']) ? count($data['rajaongkir']['results']) : 0
        ]);

    } catch (\Exception $e) {
        Log::error('Test Error', ['error' => $e->getMessage()]);

        return response()->json([
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ], 500);
    }
});
