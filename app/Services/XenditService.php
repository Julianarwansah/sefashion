<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class XenditService
{
    protected $secretKey;
    protected $baseUrl;

    public function __construct()
    {
        $this->secretKey = config('xendit.secret_key');
        $this->baseUrl = 'https://api.xendit.co'; // Xendit hanya punya 1 base URL
    }

    /**
     * Create Virtual Account
     */
    public function createVirtualAccount($data)
    {
        try {
            $params = [
                'external_id' => $data['external_id'],
                'bank_code' => $data['bank_code'],
                'name' => $data['name'],
                'expected_amount' => $data['amount'],
                'is_closed' => true,
                'is_single_use' => true,
                'expiration_date' => $data['expiration_date'] ?? now()->addDays(1)->toISOString(),
            ];

            $response = Http::withBasicAuth($this->secretKey, '')
                ->post($this->baseUrl . '/callback_virtual_accounts', $params);

            Log::info('Xendit VA Response:', [
                'status' => $response->status(),
                'response' => $response->json()
            ]);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json()
                ];
            } else {
                $errorResponse = $response->json();
                return [
                    'success' => false,
                    'error' => $errorResponse['message'] ?? 'Unknown error from Xendit'
                ];
            }

        } catch (Exception $e) {
            Log::error('Xendit VA Exception:', [
                'message' => $e->getMessage(),
                'data' => $data
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Create E-Wallet Payment
     */
    public function createEWalletPayment($data)
{
    try {
        $payload = [
            'reference_id' => $data['external_id'],
            'currency' => 'IDR',
            'amount' => $data['amount'],
            'checkout_method' => 'ONE_TIME_PAYMENT',
            'channel_code' => $data['channel_code'],

            // WAJIB → FIX ERROR CALLBACK_URL_NOT_FOUND
            'callback_url' => $data['callback_url'],

            'channel_properties' => [
                'success_redirect_url' => $data['success_redirect_url'],
                'failure_redirect_url' => $data['failure_redirect_url'],
            ],

            'metadata' => [
                'order_id' => $data['order_id']
            ]
        ];

        // Untuk OVO, tambahkan mobile number
        if ($data['channel_code'] === 'ID_OVO' && isset($data['phone_number'])) {
            $payload['channel_properties']['mobile_number'] = $data['phone_number'];
        }

        Log::info('PAYLOAD E-WALLET DIKIRIM:', $payload);

        $response = Http::withBasicAuth($this->secretKey, '')
            ->post($this->baseUrl . '/ewallets/charges', $payload);

        Log::info('Xendit E-Wallet Response:', [
            'status' => $response->status(),
            'response' => $response->json()
        ]);

        if ($response->successful()) {
            return [
                'success' => true,
                'data' => $response->json()
            ];
        } else {
            return [
                'success' => false,
                'error' => $response->json()['message'] ?? 'Unknown error from Xendit'
            ];
        }

    } catch (Exception $e) {
        Log::error('Xendit E-Wallet Exception:', [
            'message' => $e->getMessage(),
            'data' => $data
        ]);

        return [
            'success' => false,
            'error' => $e->getMessage()
        ];
    }
}


    /**
     * Create Retail Outlet Payment
     */
    public function createRetailPayment($data)
    {
        try {
            $params = [
                'external_id' => $data['external_id'],
                'retail_outlet_name' => $data['retail_outlet_name'],
                'name' => $data['name'],
                'expected_amount' => $data['amount'],
                'expiration_date' => $data['expiration_date'] ?? now()->addDays(1)->toISOString(),
            ];

            $response = Http::withBasicAuth($this->secretKey, '')
                ->post($this->baseUrl . '/fixed_payment_code', $params);

            Log::info('Xendit Retail Response:', [
                'status' => $response->status(),
                'response' => $response->json()
            ]);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json()
                ];
            } else {
                $errorResponse = $response->json();
                return [
                    'success' => false,
                    'error' => $errorResponse['message'] ?? 'Unknown error from Xendit'
                ];
            }

        } catch (Exception $e) {
            Log::error('Xendit Retail Exception:', [
                'message' => $e->getMessage(),
                'data' => $data
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Get Payment Status
     */
    public function getPaymentStatus($paymentId, $type = 'va')
    {
        try {
            $endpoint = match($type) {
                'va' => "/callback_virtual_accounts/{$paymentId}",
                'ewallet' => "/ewallets/charges/{$paymentId}",
                'retail' => "/fixed_payment_code/{$paymentId}",
                default => throw new Exception('Invalid payment type')
            };

            $response = Http::withBasicAuth($this->secretKey, '')
                ->get($this->baseUrl . $endpoint);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json()
                ];
            } else {
                $errorResponse = $response->json();
                return [
                    'success' => false,
                    'error' => $errorResponse['message'] ?? 'Unknown error from Xendit'
                ];
            }

        } catch (Exception $e) {
            Log::error('Xendit Get Payment Status Error:', [
                'message' => $e->getMessage(),
                'payment_id' => $paymentId,
                'type' => $type
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
}