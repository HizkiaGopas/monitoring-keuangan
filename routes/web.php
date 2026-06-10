<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

// 1. Halaman Utama (Sebelum Login)
Route::get('/', function () {
    return view('welcome');
});

// 2. Semua Rute yang Wajib Login (Dibungkus Middleware Auth)
Route::middleware(['auth', 'verified'])->group(function () {
    
    // Rute Dashboard Utama (Sekarang Mengarah ke DashboardController)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Rute Fitur Kategori (Categories)
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');

    // Rute Fitur Transaksi (Transactions)
    Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
    Route::post('/transactions', [TransactionController::class, 'store'])->name('transactions.store');
    Route::delete('/transactions/{transaction}', [TransactionController::class, 'destroy'])->name('transactions.destroy');

    // Rute Pengaturan Profil Akun (Bawaan Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

});

// 3. Rute Autentikasi Bawaan Laravel Breeze (Login, Register, Logout, dll)
require __DIR__.'/auth.php';