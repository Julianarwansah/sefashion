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
        $this->baseUrl = 'https://api.xendit.co';
    }

    /**
     * Create Virtual Account - FIXED VERSION
     */
    public function createVirtualAccount($data)
    {
        try {
            // GUNAKAN expected_amount, BUKAN amount
            $params = [
                'external_id' => $data['external_id'],
                'bank_code' => $data['bank_code'],
                'name' => $data['name'],
                'expected_amount' => $data['expected_amount'], // ✅ PAKAI INI
                'is_closed' => $data['is_closed'] ?? true,
                'is_single_use' => $data['is_single_use'] ?? true,
                'expiration_date' => $data['expiration_date'] ?? now()->addDays(1)->toISOString(),
            ];

            Log::info('Xendit VA Request:', $params);

            $response = Http::withBasicAuth($this->secretKey, '')
                ->timeout(30)
                ->post($this->baseUrl . '/callback_virtual_accounts', $params);

            $responseData = $response->json();

            Log::info('Xendit VA Response:', [
                'status' => $response->status(),
                'response' => $responseData
            ]);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $responseData
                ];
            } else {
                return [
                    'success' => false,
                    'error' => $responseData['message'] ?? 'Unknown error from Xendit',
                    'details' => $responseData
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
                'amount' => $data['amount'], // ✅ UNTuk E-WALLET PAKAI amount
                'checkout_method' => 'ONE_TIME_PAYMENT',
                'channel_code' => $data['channel_code'],
                'callback_url' => $data['callback_url'] ?? route('xendit.callback'),
                'channel_properties' => [
                    'success_redirect_url' => $data['success_redirect_url'],
                    'failure_redirect_url' => $data['failure_redirect_url'],
                ],
                'metadata' => [
                    'order_id' => $data['order_id']
                ]
            ];

            if ($data['channel_code'] === 'ID_OVO' && isset($data['phone_number'])) {
                $payload['channel_properties']['mobile_number'] = $data['phone_number'];
            }

            $response = Http::withBasicAuth($this->secretKey, '')
                ->post($this->baseUrl . '/ewallets/charges', $payload);

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
                'expected_amount' => $data['expected_amount'], // ✅ PAKAI expected_amount
                'expiration_date' => $data['expiration_date'] ?? now()->addDays(1)->toISOString(),
            ];

            $response = Http::withBasicAuth($this->secretKey, '')
                ->post($this->baseUrl . '/fixed_payment_code', $params);

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
            $endpoint = match ($type) {
                'va' => "/callback_virtual_accounts/{$paymentId}",
                'ewallet' => "/ewallets/charges/{$paymentId}",
                'retail' => "/fixed_payment_code/{$paymentId}",
                default => "/callback_virtual_accounts/{$paymentId}"
            };

            $response = Http::withBasicAuth($this->secretKey, '')
                ->get($this->baseUrl . $endpoint);

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
    public function simulateVAPayment($externalId, $amount)
    {
        try {
            // ✅ ENDPOINT YANG BENAR untuk simulate VA payment
            $url = $this->baseUrl . "/callback_virtual_accounts/external_id={$externalId}/simulate_payment";

            $payload = [
                'amount' => $amount
            ];

            Log::info('Simulate VA Payment Request:', [
                'url' => $url,
                'payload' => $payload,
                'external_id' => $externalId
            ]);

            $response = Http::withBasicAuth($this->secretKey, '')
                ->timeout(30)
                ->post($url, $payload);

            $responseData = $response->json();
            $statusCode = $response->status();

            Log::info('Simulate VA Payment Response:', [
                'status_code' => $statusCode,
                'response' => $responseData
            ]);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $responseData
                ];
            } else {
                return [
                    'success' => false,
                    'error' => $responseData['message'] ?? 'Unknown error from Xendit',
                    'details' => $responseData,
                    'status_code' => $statusCode
                ];
            }

        } catch (Exception $e) {
            Log::error('Simulate VA Payment Exception:', [
                'message' => $e->getMessage(),
                'external_id' => $externalId
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Create payment - Wrapper method
     */
    public function createPayment($orderId, $amount, $paymentMethod, $channel = null)
    {
        $externalId = 'ORDER-' . $orderId . '-' . time();
        $customerName = auth('customer')->user()->nama ?? 'Customer';

        if ($paymentMethod === 'va') {
            $bankCode = strtoupper($channel ?? 'BCA');
            $result = $this->createVirtualAccount([
                'external_id' => $externalId,
                'bank_code' => $bankCode,
                'name' => $customerName,
                'expected_amount' => $amount,
            ]);
            if ($result['success']) {
                return [
                    'success' => true,
                    'external_id' => $externalId,
                    'payment_url' => null,
                    'data' => $result['data']
                ];
            }
            return $result;
        }

        return ['success' => false, 'message' => 'Payment method not fully implemented yet. Use COD for now.'];
    }
}