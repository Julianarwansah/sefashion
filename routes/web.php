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

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/shop', [HomeController::class, 'shop'])->name('shop');
<<<<<<< HEAD

// About
Route::get('/about', function () {
    return view('frontend.about');
})->name('about');

// Contact
Route::get('/contact', function () {
    return view('frontend.contact');
})->name('contact');

// Product Detail
Route::get('/product/{id}', [HomeController::class, 'show'])->name('product.show');
=======
Route::get('/about', function () { return view('frontend.about'); })->name('about');
Route::get('/contact', function () { return view('frontend.contact'); })->name('contact');
Route::get('/product/{id}', [HomeController::class, 'productDetail'])->name('product.detail');
>>>>>>> 2cd4a96c689367e87f31409ec866c4920ab15c9d

// Authentication Routes
Route::get('/login', function () { return view('auth.login'); })->name('login')->middleware('guest');
Route::get('/register', function () { return view('auth.login'); })->middleware('guest');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/register', [RegisterController::class, 'register'])->name('register');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Password Reset
Route::get('/password/reset', function () { 
    return view('auth.passwords.email'); 
})->name('password.request')->middleware('guest');

Route::post('/password/email', [LoginController::class, 'sendResetLinkEmail'])->name('password.email');

/*
|--------------------------------------------------------------------------
| Customer Routes (Protected)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:customer'])->group(function () {
    Route::get('/account', function () { return view('frontend.account.dashboard'); })->name('account');
    Route::get('/my-orders', function () { return view('frontend.account.orders'); })->name('my-orders');
    Route::get('/cart', function () { return view('frontend.cart'); })->name('cart');
    Route::get('/checkout', function () { return view('frontend.checkout'); })->name('checkout');
});

/*
|--------------------------------------------------------------------------
| Admin Routes (Protected)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // Dashboard Routes
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
    
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
    
    // Shipping Management
    Route::resource('pengiriman', PengirimanController::class);
    Route::get('pengiriman/{id}/track', [PengirimanController::class, 'track'])->name('pengiriman.track');

    // Halaman calculate shipping
    Route::get('/calculate', [ShippingController::class, 'calculate'])->name('calculate');
    Route::post('/calculate/shipping-cost', [ShippingController::class, 'calculateShippingCost'])->name('calculate.shipping-cost');
    
    // API endpoints untuk dropdown
    Route::get('/calculate/provinces', [ShippingController::class, 'getProvinces'])->name('calculate.provinces');
    Route::get('/calculate/cities', [ShippingController::class, 'getCities'])->name('calculate.cities');
    Route::get('/calculate/couriers', [ShippingController::class, 'getCouriers'])->name('calculate.couriers');
    Route::get('/calculate/api-status', [ShippingController::class, 'checkApiStatus'])->name('calculate.api-status');
    
    // Untuk update alamat customer
    Route::post('/calculate/update-customer-address', [ShippingController::class, 'updateCustomerAddress'])->name('calculate.update-customer-address');
});