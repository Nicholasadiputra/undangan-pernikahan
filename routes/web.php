<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UndanganController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Dashboard\TamuController;
use App\Http\Controllers\Dashboard\AdminController;
use App\Http\Controllers\Dashboard\LandingController;

Route::get('/', [UndanganController::class, 'index'])->name('undangan.index');

// Tamu masuk langsung tanpa password
Route::post('/tamu-masuk', [LoginController::class, 'tamuMasuk'])->name('tamu.masuk');

// Halaman utama undangan (hanya setelah klik masuk)
Route::get('/utama', [UndanganController::class, 'utama'])->name('undangan.utama');

// RSVP tamu
Route::post('/proses-rsvp', [UndanganController::class, 'rsvp'])->name('rsvp');

// Logout (untuk semua role)
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

// DASHBOARD LOGIN
Route::get('/dashboard/login', [LoginController::class, 'showAdminLogin'])->name('dashboard.login');
Route::post('/dashboard/login', [LoginController::class, 'adminLogin'])->name('dashboard.login.post');


// DASHBOARD (PROTECTED)
Route::prefix('dashboard')->middleware('admin')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard.index');
    Route::get('/data-tamu', [TamuController::class, 'page'])->name('dashboard.tamu');
    Route::get('/data-admin', [AdminController::class, 'index'])->name('dashboard.admin');
    Route::get('/edit-landing', [LandingController::class, 'index'])->name('dashboard.landing');
});


// API TAMU (PROTECTED)
Route::prefix('api')->middleware('admin')->group(function () {
    Route::get('/tamu', [TamuController::class, 'index']);
    Route::post('/tamu', [TamuController::class, 'store']);
    Route::put('/tamu/{id}', [TamuController::class, 'update']);
    Route::delete('/tamu/{id}', [TamuController::class, 'destroy']);
});