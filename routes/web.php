<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\GoogleAuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\PesananController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\PengirimanController;
use App\Http\Controllers\ShippingController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\XenditTestController;
use App\Http\Controllers\ChatbotController;


// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/shop', [HomeController::class, 'shop'])->name('shop');
Route::get('/about', fn() => view('frontend.about'))->name('about');
Route::get('/contact', fn() => view('frontend.contact'))->name('contact');
Route::get('/product/{id}', [HomeController::class, 'show'])->name('product.show');

// Authentication Routes
Route::get('/login', fn() => view('auth.login'))->name('login')->middleware('guest:customer');
Route::get('/register', fn() => view('auth.register'))->name('register')->middleware('guest:customer');

Route::post('/login', [LoginController::class, 'login']);
Route::post('/register', [RegisterController::class, 'register'])->name('register.submit');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Google OAuth Routes
Route::get('/auth/google', [GoogleAuthController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [GoogleAuthController::class, 'handleGoogleCallback'])->name('auth.google.callback');

// Password Reset
Route::get('/password/reset', fn() => view('auth.passwords.email'))
    ->name('password.request')
    ->middleware('guest');

Route::post('/password/email', [LoginController::class, 'sendResetLinkEmail'])->name('password.email');

// Chatbot Routes (accessible to all)
Route::post('/chatbot/chat', [ChatbotController::class, 'chat'])->name('chatbot.chat');
Route::get('/chatbot/history', [ChatbotController::class, 'history'])->name('chatbot.history');
Route::delete('/chatbot/history', [ChatbotController::class, 'clearHistory'])->name('chatbot.clear');


// ========================================================================
// XENDIT WEBHOOK ROUTES
// ========================================================================
Route::post('/webhook/xendit', [CheckoutController::class, 'handleWebhook'])->name('webhook.xendit');
Route::post('/xendit/callback', [CheckoutController::class, 'handleWebhook'])->name('xendit.callback');


/*
|--------------------------------------------------------------------------
| Customer Routes (Protected)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:customer'])->group(function () {
    Route::get('/product/{id}', [HomeController::class, 'show'])->name('product.show');

    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // Cart routes
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::put('/cart/{id}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{id}', [CartController::class, 'remove'])->name('cart.remove');
    Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');
    Route::get('/cart/count', [CartController::class, 'getCount'])->name('cart.count');

    // Checkout routes
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
    Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');
    Route::post('/checkout/calculate-shipping', [CheckoutController::class, 'calculateShipping'])->name('checkout.shipping');
    Route::get('/checkout/cities', [CheckoutController::class, 'getCities'])->name('checkout.cities');

    // Payment Status Routes
    Route::get('/payment/status/{orderId}', [CheckoutController::class, 'checkPaymentStatus'])->name('payment.status');
    Route::get('/payment/success/{orderId}', [CheckoutController::class, 'paymentSuccess'])->name('payment.success');
    Route::get('/payment/failed/{orderId}', [CheckoutController::class, 'paymentFailed'])->name('payment.failed');

    // Waiting Payment
    Route::get('/order/success/{id}', function ($id) {
        $pemesanan = \App\Models\Pemesanan::with('pembayaran')->find($id);

        if (!$pemesanan) {
            return redirect()->route('home')->with('error', 'Order not found');
        }

        if ($pemesanan->pembayaran && $pemesanan->pembayaran->status_pembayaran === \App\Models\Pembayaran::STATUS_SUDAH_BAYAR) {
            return redirect()->route('payment.success', $id);
        }

        if ($pemesanan->pembayaran && $pemesanan->pembayaran->status_pembayaran === \App\Models\Pembayaran::STATUS_GAGAL) {
            return redirect()->route('payment.failed', $id);
        }

        return view('frontend.order-success', [
            'orderId' => $id,
            'pemesanan' => $pemesanan
        ]);
    })->name('order.success');

    // My Orders Routes
    Route::get('/my-orders', [OrderController::class, 'index'])->name('my-orders');
    Route::get('/order/{id}', [OrderController::class, 'show'])->name('order.detail');
    Route::post('/order/{id}/cancel', [OrderController::class, 'cancel'])->name('order.cancel');
});


/*
|--------------------------------------------------------------------------
| Admin Routes (Protected)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:admin'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/dashboard/chart-data', [DashboardController::class, 'getChartData'])->name('dashboard.chart');
    Route::get('/dashboard/real-time-stats', [DashboardController::class, 'getRealTimeStats'])->name('dashboard.stats');

    // Admin Management
    Route::resource('adminn', AdminController::class);
    Route::get('/cari-admin', [AdminController::class, 'search'])->name('adminn.search');

    // Customer Management
    Route::resource('customer', CustomerController::class);
    Route::get('/cari-customer', [CustomerController::class, 'search'])->name('customer.search');


    // =========================================================================
    //  *** ROUTES PRODUK - PERBAIKI URUTAN YANG BENAR ***
    // =========================================================================

    // 1. ROUTES KHUSUS GAMBAR HARUS DULUAN (LEBIH SPESIFIK)
    Route::delete('/produk/{produk}/delete-image/{gambar}', [ProdukController::class, 'deleteImage'])
        ->name('produk.delete-image');

    Route::post('/produk/{produk}/set-primary-image/{gambar}', [ProdukController::class, 'setPrimaryImage'])
        ->name('produk.set-primary-image');

    Route::post('/produk/{produk}/tambah-gambar', [ProdukController::class, 'tambahGambar'])
        ->name('produk.tambah-gambar');

    Route::get('/produk/{produk}/images', [ProdukController::class, 'getImages'])
        ->name('produk.get-images');

    // Route search produk
    Route::get('/cari-produk', [ProdukController::class, 'search'])->name('produk.search');

    // 2. ROUTE RESOURCE SETELAH ROUTE KHUSUS
    Route::resource('produk', ProdukController::class);


    // Order Management
    Route::resource('pesanan', PesananController::class);
    Route::get('/cari-pesanan', [PesananController::class, 'filter'])->name('pesanan.filter');
    Route::post('/pesanan/{id}/cancel', [PesananController::class, 'cancel'])->name('pesanan.cancel');

    // Pembayaran
    Route::resource('pembayaran', PembayaranController::class);
    Route::get('/cari-pembayaran', [PembayaranController::class, 'filter'])->name('pembayaran.filter');
    Route::post('/pembayaran/{id}/mark-paid', [PembayaranController::class, 'markAsPaid'])->name('pembayaran.mark-paid');
    Route::post('/pembayaran/{id}/mark-expired', [PembayaranController::class, 'markAsExpired'])->name('pembayaran.mark-expired');
    Route::post('/pembayaran/{id}/update-status', [PembayaranController::class, 'updateStatus'])->name('pembayaran.update-status');

    // Shipping Management
    Route::resource('pengiriman', PengirimanController::class);
    Route::get('/cari-pengiriman', [PengirimanController::class, 'filter'])->name('pengiriman.filter');
    Route::get('/pengiriman/{id}/track', [PengirimanController::class, 'track'])->name('pengiriman.track');
});

// Fake Pay Dev
Route::get('/dev/fake-pay/{orderId}', [CheckoutController::class, 'devMarkAsPaid'])->name('dev.fake-pay');

// Xendit Test
Route::prefix('xendit/test')->group(function () {
    Route::get('/connection', [XenditTestController::class, 'testConnection'])->name('xendit.test.connection');
    Route::get('/webhook', [XenditTestController::class, 'testWebhook'])->name('xendit.test.webhook');
    Route::get('/va', [XenditTestController::class, 'testVA'])->name('xendit.test.va');
    Route::get('/balance', [XenditTestController::class, 'testBalance'])->name('xendit.test.balance');
    Route::get('/page', [XenditTestController::class, 'testPage'])->name('xendit.test.page');
});

// Binderbyte Test
Route::get('/test-binderbyte', function (App\Services\BinderbyteService $service) {
    $provinces = $service->getProvinces();
    return response()->json([
        'api_key_configured' => !empty(config('services.binderbyte.key')),
        'provinces_count' => count($provinces),
        'sample_provinces' => array_slice($provinces, 0, 5)
    ]);
});

Route::get('/test-cities', function (App\Services\BinderbyteService $service) {
    // Test DKI Jakarta (31)
    $cities = $service->getCities('31');
    return response()->json([
        'success' => !empty($cities),
        'count' => count($cities),
        'sample' => $cities // Show all to find Jakarta Pusat
    ]);
});

// RajaOngkir Test
Route::get('/test-rajaongkir', function () {
    $apiKey = config('services.rajaongkir.key');
    $baseUrl = 'https://api.rajaongkir.com/starter';

    Log::info('Testing RajaOngkir API', [
        'api_key' => substr($apiKey, 0, 10) . '...',
        'base_url' => $baseUrl
    ]);

    // Test 1: Get Provinces
    try {
        $url = "{$baseUrl}/province";

        $response = Http::withHeaders([
            'key' => $apiKey
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