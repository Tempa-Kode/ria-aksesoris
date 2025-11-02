<?php

use Illuminate\Support\Facades\Route;

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/login', [App\Http\Controllers\HomeController::class, 'index'])->name('login');
Route::post('/login', [App\Http\Controllers\AuthController::class, 'login'])->name('auth.login');
Route::get('/dashboard', [App\Http\Controllers\HomeController::class, 'dashboard'])->middleware('auth')->name('dashboard');
Route::post('/logout', [App\Http\Controllers\AuthController::class, 'logout'])->middleware('auth')->name('auth.logout');

// Admin Routes
Route::middleware('auth')->group(function () {
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
