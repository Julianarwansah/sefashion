<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\PesananController;
use App\Http\Controllers\PengirimanController;
use App\Http\Controllers\ShippingController;

Route::get('/', function () {
    return view('admin.pages.dashboard');
});
Route::resource('admin', AdminController::class);
Route::resource('customer', CustomerController::class);
Route::resource('produk', ProdukController::class);
// Route untuk mengatur gambar utama
Route::post('/produk/{produk}/set-primary-image/{gambar}', [ProdukController::class, 'setPrimaryImage'])
    ->name('produk.set-primary-image');

// Route untuk menghapus gambar
Route::delete('/produk/{produk}/delete-image/{gambar}', [ProdukController::class, 'deleteImage'])
    ->name('produk.delete-image');
Route::resource('pesanan', PesananController::class);
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