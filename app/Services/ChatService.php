<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class ChatService
{
    protected $client;
    protected $apiKey;
    protected $apiUrl = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent';

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

            // Augment context with specific product data based on user query
            $productContext = $this->augmentContextWithProductData($message);
            if ($productContext) {
                $context .= "\n\nINFORMASI TAMBAHAN DARI DATABASE:\n" . $productContext;
            }

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
                                [
                                    'text' => $fullPrompt
                                ]
                            ]
                        ]
                    ]
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
     * Augment context with product data based on keywords
     */
    protected function augmentContextWithProductData(string $message): string
    {
        $message = strtolower($message);
        $keywords = ['stok', 'harga', 'cari', 'produk', 'baju', 'celana', 'kemeja', 'kaos', 'jaket', 'dress', 'rok', 'sepatu', 'tas', 'topi', 'warna', 'ukuran'];

        // Check if message contains any keywords
        $hasKeyword = false;
        foreach ($keywords as $keyword) {
            if (str_contains($message, $keyword)) {
                $hasKeyword = true;
                break;
            }
        }

        if (!$hasKeyword) {
            return '';
        }

        // Extract potential product names (simple heuristic: words after "cari", "stok", "harga")
        // For now, let's just search for any product that matches words in the message
        $words = explode(' ', $message);
        $searchTerms = array_filter($words, function ($word) use ($keywords) {
            return strlen($word) > 2
                && !in_array($word, ['yang', 'dan', 'atau', 'dari', 'untuk', 'dengan', 'saya', 'kamu', 'toko', 'halo', 'tolong', 'bisa', 'minta', 'info', 'cek', 'lihat', 'apa', 'saja'])
                && !in_array($word, $keywords);
        });

        if (empty($searchTerms)) {
            // If no specific terms, maybe list latest products
            $products = \App\Models\Produk::latest()->take(5)->get();
            $info = "Daftar Produk Terbaru:\n";
            foreach ($products as $product) {
                $info .= "- {$product->nama_produk} (Stok: {$product->total_stok})\n";
            }
            return $info;
        }

        // Search products
        $query = \App\Models\Produk::query();

        $query->where(function ($q) use ($searchTerms) {
            foreach ($searchTerms as $term) {
                $q->orWhere('nama_produk', 'like', "%{$term}%")
                    ->orWhere('deskripsi', 'like', "%{$term}%")
                    ->orWhere('kategori', 'like', "%{$term}%");
            }
        });

        $products = $query->with([
            'detailUkuran' => function ($q) {
                $q->where('stok', '>', 0);
            }
        ])->take(5)->get();

        if ($products->isEmpty()) {
            return "Tidak ditemukan produk yang cocok dengan kata kunci pencarian.";
        }

        $info = "Ditemukan " . $products->count() . " produk relevan:\n";
        foreach ($products as $product) {
            $info .= "1. Nama: {$product->nama_produk}\n";
            $info .= "   Kategori: {$product->kategori}\n";
            $info .= "   Total Stok: {$product->total_stok}\n";

            // Price range
            $minPrice = $product->detailUkuran->min('harga');
            $maxPrice = $product->detailUkuran->max('harga');
            if ($minPrice == $maxPrice) {
                $info .= "   Harga: Rp " . number_format($minPrice, 0, ',', '.') . "\n";
            } else {
                $info .= "   Harga: Rp " . number_format($minPrice, 0, ',', '.') . " - Rp " . number_format($maxPrice, 0, ',', '.') . "\n";
            }

            // Available sizes/colors
            $variants = [];
            foreach ($product->detailUkuran as $detail) {
                $size = $detail->ukuran;
                $color = $detail->detailWarna->nama_warna ?? 'Umum';
                $stock = $detail->stok;
                $variants[] = "{$size} ({$color}): {$stock}";
            }
            if (!empty($variants)) {
                $info .= "   Varian Tersedia: " . implode(', ', array_slice($variants, 0, 5)) . (count($variants) > 5 ? ", dll" : "") . "\n";
            }
            $info .= "\n";
        }

        return $info;
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
        $context .= "Gunakan data produk yang diberikan untuk menjawab pertanyaan tentang stok dan harga secara akurat.\n";

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