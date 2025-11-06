<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\PesananController;
use App\Http\Controllers\PengirimanController;
use App\Http\Controllers\ShippingController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\HomeController;

/*
|--------------------------------------------------------------------------
| Guest Routes (Frontend)
|--------------------------------------------------------------------------
*/

// Home Page
Route::get('/', [HomeController::class, 'index'])->name('home');

// Shop
Route::get('/shop', [HomeController::class, 'shop'])->name('shop');

// About
Route::get('/about', function () {
    return view('frontend.about');
})->name('about');

// Contact
Route::get('/contact', function () {
    return view('frontend.contact');
})->name('contact');

// Product Detail
Route::get('/product/{id}', [HomeController::class, 'productDetail'])->name('product.detail');

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/

// Login & Register Page (Combined)
Route::get('/login', function () {
    return view('auth.login');
})->name('login')->middleware('guest');

Route::get('/register', function () {
    return view('auth.login');
})->middleware('guest');

// Login Process
Route::post('/login', [LoginController::class, 'login']);

// Register Process
Route::post('/register', [RegisterController::class, 'register'])->name('register');

// Logout
Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// Password Reset Routes
Route::get('/password/reset', function () {
    return view('auth.passwords.email');
})->name('password.request')->middleware('guest');

Route::post('/password/email', [LoginController::class, 'sendResetLinkEmail'])->name('password.email');

/*
|--------------------------------------------------------------------------
| Customer/User Routes (Protected)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'user'])->group(function () {
    // My Account
    Route::get('/account', function () {
        return view('frontend.account.dashboard');
    })->name('account');
    
    // Orders
    Route::get('/my-orders', function () {
        return view('frontend.account.orders');
    })->name('my-orders');
    
    // Cart
    Route::get('/cart', function () {
        return view('frontend.cart');
    })->name('cart');
    
    // Checkout
    Route::get('/checkout', function () {
        return view('frontend.checkout');
    })->name('checkout');
});

/*
|--------------------------------------------------------------------------
| Admin Routes (Protected)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    // Dashboard
    Route::get('/', function () {
        return view('admin.pages.dashboard');
    })->name('admin.dashboard');
    
    // Admin Management
    Route::resource('admins', AdminController::class);
    
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

    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/shop', [HomeController::class, 'shop'])->name('shop');
    Route::get('/product/{id}', [HomeController::class, 'show'])->name('product.show');
});