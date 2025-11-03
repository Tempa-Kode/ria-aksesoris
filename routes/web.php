<?php

use Illuminate\Support\Facades\Route;

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/login', [App\Http\Controllers\HomeController::class, 'index'])->name('login');
Route::post('/login', [App\Http\Controllers\AuthController::class, 'login'])->name('auth.login');
Route::get('/dashboard', [App\Http\Controllers\HomeController::class, 'dashboard'])->middleware('auth')->name('dashboard');
Route::post('/logout', [App\Http\Controllers\AuthController::class, 'logout'])->middleware('auth')->name('logout');

// Product Detail Routes
Route::get('/produk/{id}', [App\Http\Controllers\ProdukDetailController::class, 'show'])->name('produk.detail');

// Cart & Checkout Routes
Route::get('/cart', [App\Http\Controllers\CartController::class, 'index'])->name('cart');
Route::get('/checkout', [App\Http\Controllers\CartController::class, 'checkout'])->name('checkout');
Route::post('/checkout/process', [App\Http\Controllers\CartController::class, 'processCheckout'])->name('checkout.process');
Route::get('/order/confirmation/{id}', [App\Http\Controllers\CartController::class, 'orderConfirmation'])->name('order.confirmation');
Route::post('/order/payment-proof/{id}', [App\Http\Controllers\CartController::class, 'uploadPaymentProof'])->name('order.payment.proof');

// Akun Routes (Customer)
Route::middleware('auth')->prefix('akun')->name('akun.')->group(function () {
    Route::get('/', [App\Http\Controllers\AkunController::class, 'index'])->name('saya');
    Route::get('/pesanan', [App\Http\Controllers\AkunController::class, 'pesanan'])->name('pesanan');
    Route::get('/pesanan/{id}', [App\Http\Controllers\AkunController::class, 'pesananDetail'])->name('pesanan.detail');
    Route::get('/alamat', [App\Http\Controllers\AkunController::class, 'alamat'])->name('alamat');
    Route::post('/alamat', [App\Http\Controllers\AkunController::class, 'updateAlamat'])->name('alamat.update');
    Route::get('/edit', [App\Http\Controllers\AkunController::class, 'edit'])->name('edit');
    Route::post('/edit', [App\Http\Controllers\AkunController::class, 'update'])->name('update');
});

// Admin Routes
Route::middleware('auth')->group(function () {
    // Profile Routes
    Route::get('profile', [App\Http\Controllers\ProfileController::class, 'index'])->name('profile.index');
    Route::put('profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::put('profile/password', [App\Http\Controllers\ProfileController::class, 'updatePassword'])->name('profile.password.update');

    Route::resource('admin', App\Http\Controllers\AdminController::class);
    Route::resource('karyawan', App\Http\Controllers\KaryawanController::class);
    Route::resource('customer', App\Http\Controllers\CustomerController::class);
    Route::resource('kategori', App\Http\Controllers\KategoriController::class);

    // Custom routes HARUS sebelum resource route
    Route::delete('produk/gambar/{id}', [App\Http\Controllers\ProdukController::class, 'deleteGambar'])->name('produk.gambar.delete');
    Route::delete('produk/jenis/{id}', [App\Http\Controllers\ProdukController::class, 'deleteJenis'])->name('produk.jenis.delete');
    Route::post('produk/{id}/stok/tambah', [App\Http\Controllers\ProdukController::class, 'tambahStok'])->name('produk.stok.tambah');

    Route::resource('produk', App\Http\Controllers\ProdukController::class);
});
