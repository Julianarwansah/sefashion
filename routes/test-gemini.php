<?php

use Illuminate\Support\Facades\Route;
use GuzzleHttp\Client;

Route::get('/test-gemini', function () {
    $apiKey = env('GOOGLE_AI_API_KEY');

    if (empty($apiKey)) {
        return response()->json([
            'error' => 'API Key not set in .env file'
        ]);
    }

    try {
        $client = new Client();

        $response = $client->post('https://generativelanguage.googleapis.com/v1/models/gemini-1.5-flash:generateContent', [
            'headers' => [
                'Content-Type' => 'application/json',
            ],
            'query' => [
                'key' => $apiKey,
            ],
            'json' => [
                'contents' => [
                    [
                        'parts' => [
                            [
                                'text' => 'Halo, siapa kamu?'
                            ]
                        ]
                    ]
                ]
            ],
        ]);

        $data = json_decode($response->getBody()->getContents(), true);

        return response()->json([
            'success' => true,
            'api_key_set' => !empty($apiKey),
            'api_key_preview' => substr($apiKey, 0, 10) . '...',
            'response' => $data
        ]);

    } catch (\GuzzleHttp\Exception\ClientException $e) {
        $errorBody = $e->getResponse()->getBody()->getContents();
        return response()->json([
            'error' => 'API Error',
            'message' => $e->getMessage(),
            'details' => json_decode($errorBody, true)
        ], 400);
    } catch (\Exception $e) {
        return response()->json([
            'error' => 'General Error',
            'message' => $e->getMessage()
        ], 500);
    }
});
