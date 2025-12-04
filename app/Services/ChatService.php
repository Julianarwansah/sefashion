<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class ChatService
{
    protected $client;
    protected $apiKey;
    protected $apiUrl = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent';

    public function __construct()
    {
        $this->client = new Client();
        $this->apiKey = env('GOOGLE_AI_API_KEY');
    }

    /**
     * Send message to Google Gemini AI
     */
    public function chat(string $message, ?int $customerId = null): array
    {
        try {
            // Build context from database
            $context = $this->buildDatabaseContext();

            // Build full prompt with context
            $fullPrompt = $this->buildPrompt($message, $context, $customerId);

            // Call Google Gemini API
            $response = $this->client->post($this->apiUrl, [
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
                'query' => [
                    'key' => $this->apiKey,
                ],
                'json' => [
                    'contents' => [
                        [
                            'parts' => [
                                ['text' => $fullPrompt]
                            ]
                        ]
                    ],
                    'generationConfig' => [
                        'temperature' => 0.7,
                        'maxOutputTokens' => 1024,
                    ],
                ],
            ]);

            $data = json_decode($response->getBody()->getContents(), true);

            // Extract response text
            $aiResponse = $data['candidates'][0]['content']['parts'][0]['text'] ?? 'Maaf, saya tidak dapat memproses permintaan Anda saat ini.';

            return [
                'success' => true,
                'response' => $aiResponse,
            ];

        } catch (\Exception $e) {
            Log::error('Chatbot error: ' . $e->getMessage());

            return [
                'success' => false,
                'response' => 'Maaf, terjadi kesalahan. Silakan coba lagi nanti.',
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Build database context for AI
     */
    protected function buildDatabaseContext(): string
    {
        $context = "Anda adalah asisten virtual untuk toko fashion online 'SE Fashion'. Berikut adalah informasi database:\n\n";

        // Get product count
        $productCount = \DB::table('produk')->count();
        $context .= "- Total Produk: {$productCount}\n";

        // Get categories (assuming there's a kategori field)
        $categories = \DB::table('produk')
            ->select('kategori')
            ->distinct()
            ->pluck('kategori')
            ->toArray();

        if (!empty($categories)) {
            $context .= "- Kategori: " . implode(', ', $categories) . "\n";
        }

        // Get price range
        $priceRange = \DB::table('detail_ukuran')
            ->selectRaw('MIN(harga) as min_price, MAX(harga) as max_price')
            ->first();

        if ($priceRange) {
            $context .= "- Range Harga: Rp " . number_format($priceRange->min_price, 0, ',', '.') .
                " - Rp " . number_format($priceRange->max_price, 0, ',', '.') . "\n";
        }

        $context .= "\nAnda dapat membantu customer dengan:\n";
        $context .= "1. Informasi produk dan harga\n";
        $context .= "2. Ketersediaan stok\n";
        $context .= "3. Kategori produk\n";
        $context .= "4. Rekomendasi produk\n";
        $context .= "5. Informasi umum tentang toko\n\n";

        $context .= "Jawab dengan ramah, informatif, dan dalam Bahasa Indonesia.\n";

        return $context;
    }

    /**
     * Build full prompt with context
     */
    protected function buildPrompt(string $message, string $context, ?int $customerId): string
    {
        $prompt = $context;

        // Add customer context if logged in
        if ($customerId) {
            $customer = \DB::table('customers')->where('id_customer', $customerId)->first();
            if ($customer) {
                $prompt .= "Customer: {$customer->nama}\n";

                // Get customer's order count
                $orderCount = \DB::table('pemesanan')
                    ->where('id_customer', $customerId)
                    ->count();

                $prompt .= "Jumlah Pesanan: {$orderCount}\n\n";
            }
        }

        $prompt .= "Pertanyaan Customer: {$message}\n\n";
        $prompt .= "Berikan jawaban yang membantu dan relevan:";

        return $prompt;
    }

    /**
     * Execute safe database query (read-only)
     */
    public function executeQuery(string $query): array
    {
        try {
            // Security: Only allow SELECT queries
            if (!preg_match('/^\s*SELECT/i', trim($query))) {
                return [
                    'success' => false,
                    'error' => 'Only SELECT queries are allowed',
                ];
            }

            // Execute query
            $results = \DB::select($query);

            return [
                'success' => true,
                'data' => $results,
            ];

        } catch (\Exception $e) {
            Log::error('Query execution error: ' . $e->getMessage());

            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }
}
