<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ProdukController;

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