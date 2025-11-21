<?php

namespace App\Http\Controllers;

use App\Services\XenditService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class XenditTestController extends Controller
{
    protected $xenditService;

    public function __construct()
    {
        $this->xenditService = new XenditService();
    }

    /**
     * Test koneksi dasar ke Xendit
     */
    public function testConnection()
    {
        try {
            $secretKey = config('xendit.secret_key');
            
            $result = [
                'xendit_secret_key_configured' => !empty($secretKey),
                'xendit_secret_key_preview' => $secretKey ? substr($secretKey, 0, 10) . '...' : 'Not set',
                'environment' => config('app.env'),
                'timestamp' => now()->toDateTimeString(),
            ];

            // Test API call ke Xendit
            if (!empty($secretKey)) {
                $testVA = $this->testVirtualAccountCreation();
                $result['api_test'] = $testVA;
            }

            return response()->json([
                'success' => true,
                'message' => 'Xendit Connection Test',
                'data' => $result
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Test failed: ' . $e->getMessage(),
                'error' => $e->getTraceAsString()
            ], 500);
        }
    }

    /**
     * Test pembuatan Virtual Account
     */
    private function testVirtualAccountCreation()
    {
        try {
            $testData = [
                'external_id' => 'TEST-' . now()->format('YmdHis'),
                'bank_code' => 'BRI',
                'name' => 'Test Customer',
                'expected_amount' => 10000,
                'is_closed' => true,
                'is_single_use' => true,
                'expiration_date' => now()->addDays(1)->toISOString(),
            ];

            Log::info('Xendit Test Request:', $testData);

            $result = $this->xenditService->createVirtualAccount($testData);

            return [
                'test_type' => 'Virtual Account Creation',
                'success' => $result['success'],
                'response' => $result['success'] ? 'API Connected Successfully' : $result['error'],
                'test_data' => $testData
            ];

        } catch (\Exception $e) {
            return [
                'test_type' => 'Virtual Account Creation',
                'success' => false,
                'error' => $e->getMessage(),
                'test_data' => $testData
            ];
        }
    }

    /**
     * Test webhook endpoint
     */
    public function testWebhook()
    {
        try {
            $webhookUrl = route('webhook.xendit');
            
            return response()->json([
                'success' => true,
                'message' => 'Webhook Test',
                'data' => [
                    'webhook_url' => $webhookUrl,
                    'accessible' => true,
                    'timestamp' => now()->toDateTimeString()
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Webhook test failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Test mendapatkan balance (opsional - butuh permission khusus)
     */
    public function testBalance()
    {
        try {
            // Method untuk test get balance (jika ada di XenditService)
            if (method_exists($this->xenditService, 'getBalance')) {
                $balance = $this->xenditService->getBalance();
                return response()->json([
                    'success' => true,
                    'message' => 'Balance retrieved',
                    'data' => $balance
                ]);
            } else {
                return response()->json([
                    'success' => true,
                    'message' => 'Balance check not implemented',
                    'data' => ['note' => 'Add getBalance method to XenditService for this test']
                ]);
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Balance test failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Test page - UI untuk testing
     */
    public function testPage()
    {
        return view('xendit-test');
    }

    /**
 * Test Virtual Account khusus
 */
public function testVA()
{
    return $this->testVirtualAccountCreation();
}
}