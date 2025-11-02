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
});
