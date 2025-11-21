<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
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


// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/shop', [HomeController::class, 'shop'])->name('shop');
Route::get('/about', function () { 
    return view('frontend.about'); 
})->name('about');
Route::get('/contact', function () { 
    return view('frontend.contact'); 
})->name('contact');
Route::get('/product/{id}', [HomeController::class, 'show'])->name('product.show');

// Authentication Routes
Route::get('/login', function () { 
    return view('auth.login'); 
})->name('login')->middleware('guest');

Route::get('/register', function () { 
    return view('auth.register'); 
})->name('register')->middleware('guest');

Route::post('/login', [LoginController::class, 'login']);
Route::post('/register', [RegisterController::class, 'register'])->name('register.submit');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Password Reset
Route::get('/password/reset', function () { 
    return view('auth.passwords.email'); 
})->name('password.request')->middleware('guest');

Route::post('/password/email', [LoginController::class, 'sendResetLinkEmail'])->name('password.email');

// =========================================================================
// XENDIT WEBHOOK ROUTES (HARUS PUBLIC KARENA DIAKSES OLEH XENDIT SERVER)
// =========================================================================
Route::post('/webhook/xendit', [CheckoutController::class, 'handleWebhook'])->name('webhook.xendit');
Route::post('/xendit/callback', [CheckoutController::class, 'handleWebhook'])->name('xendit.callback'); 

/*
|--------------------------------------------------------------------------
| Customer Routes (Protected)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:customer'])->group(function () {
    // Product Detail - HANYA bisa diakses setelah login
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
    
    // Payment Status Routes
    Route::get('/payment/status/{orderId}', [CheckoutController::class, 'checkPaymentStatus'])->name('payment.status');
    Route::get('/payment/success/{orderId}', [CheckoutController::class, 'paymentSuccess'])->name('payment.success');
    Route::get('/payment/failed/{orderId}', [CheckoutController::class, 'paymentFailed'])->name('payment.failed');
    
    // Order Success Route - Redirect to waiting payment
    Route::get('/order/success/{id}', function ($id) {
        $pemesanan = \App\Models\Pemesanan::with('pembayaran')->find($id);

        if (!$pemesanan) {
            return redirect()->route('home')->with('error', 'Order not found');
        }

        // If already paid, redirect to payment success
        if ($pemesanan->pembayaran && $pemesanan->pembayaran->status_pembayaran === \App\Models\Pembayaran::STATUS_SUDAH_BAYAR) {
            return redirect()->route('payment.success', $id);
        }

        // If payment failed, redirect to payment failed
        if ($pemesanan->pembayaran && $pemesanan->pembayaran->status_pembayaran === \App\Models\Pembayaran::STATUS_GAGAL) {
            return redirect()->route('payment.failed', $id);
        }

        // Otherwise show waiting payment page
        return view('frontend.waiting-payment', [
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
    
    // Dashboard Routes
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // AJAX Routes untuk dashboard
    Route::get('/dashboard/chart-data', [DashboardController::class, 'getChartData'])->name('dashboard.chart');
    Route::get('/dashboard/real-time-stats', [DashboardController::class, 'getRealTimeStats'])->name('dashboard.stats');
    
    // AJAX Routes untuk dashboard
    Route::get('/dashboard/chart-data', [DashboardController::class, 'getChartData'])->name('dashboard.chart');
    Route::get('/dashboard/real-time-stats', [DashboardController::class, 'getRealTimeStats'])->name('dashboard.stats');
    
    // Admin Management
    Route::resource('adminn', AdminController::class);
    
    // Customer Management
    Route::resource('customer', CustomerController::class);
    
    // Product Management
    Route::resource('produk', ProdukController::class);
    
    // Route untuk mengatur gambar utama
    Route::post('/produk/{produk}/set-primary-image/{gambar}', [ProdukController::class, 'setPrimaryImage'])
        ->name('produk.set-primary-image');

    // Route untuk menghapus gambar
    Route::delete('/produk/{produk}/delete-image/{gambar}', [ProdukController::class, 'deleteImage'])
        ->name('produk.delete-image');
    
    // Order Management
    Route::resource('pesanan', PesananController::class);
    
    Route::resource('pembayaran', PembayaranController::class);

    // Tambahan untuk mark as paid
    Route::post('/pembayaran/{id}/mark-paid', 
        [PembayaranController::class, 'markAsPaid']
    )->name('pembayaran.mark-paid');

    // Tambahan untuk mark as expired jika perlu
    Route::post('/pembayaran/{id}/mark-expired', 
        [PembayaranController::class, 'markAsExpired']
    )->name('pembayaran.mark-expired');
    // Tambahkan ini (UPDATE STATUS PEMBAYARAN)
        Route::post('/pembayaran/{id}/update-status', [PembayaranController::class, 'updateStatus'])
            ->name('pembayaran.update-status');
    // Shipping Management
    Route::resource('pengiriman', PengirimanController::class);
    Route::get('pengiriman/{id}/track', [PengirimanController::class, 'track'])->name('pengiriman.track');
});
Route::get('/dev/fake-pay/{orderId}', [CheckoutController::class, 'devMarkAsPaid'])
    ->name('dev.fake-pay');

    // Xendit Test Routes (bisa diakses tanpa auth untuk testing)
Route::prefix('xendit/test')->group(function () {
    Route::get('/connection', [XenditTestController::class, 'testConnection'])->name('xendit.test.connection');
    Route::get('/webhook', [XenditTestController::class, 'testWebhook'])->name('xendit.test.webhook');
    Route::get('/va', [XenditTestController::class, 'testVA'])->name('xendit.test.va');
    Route::get('/balance', [XenditTestController::class, 'testBalance'])->name('xendit.test.balance');
    Route::get('/page', [XenditTestController::class, 'testPage'])->name('xendit.test.page');
});