<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InventoryReportController;
use App\Http\Controllers\SparePartController;
use App\Http\Controllers\StockTransactionController;
use App\Http\Controllers\SupplierController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

// 1. Halaman Utama (Sekarang diarahkan langsung ke Login)
Route::get('/', [AuthController::class, 'showLogin'])->middleware('guest');

// 2. Rute Autentikasi (Hanya bisa diakses jika BELUM login)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    // Web perusahaan gk perlu perlu register
    // Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    // Route::post('/register', [AuthController::class, 'register']);
});

/**
 * Grup Akses: Admin Gudang (Warehouse Admin)
 * Menangani UC-01, UC-02, UC-03, dan UC-04
 */
Route::middleware(['auth', 'role:warehouse_admin'])->prefix('admin')->group(function () {
// Route::prefix('admin')->group(function () {
    // UC-01: Mengelola Data Suku Cadang
    Route::get('/management', [SparePartController::class, 'index'])->name('admin.management');
    Route::post('/spare-parts/store', [SparePartController::class, 'store'])->name('spare-parts.store');
    // UC-04: Melihat & Update Lokasi Rak
    Route::get('/locations', [SparePartController::class, 'rackLocations'])->name('admin.locations');
    // UC-02: Mencatat Stok Masuk (Inbound)
    Route::get('/inbound', [StockTransactionController::class, 'inboundIndex'])->name('admin.inbound');
    Route::post('/inbound/store', [StockTransactionController::class, 'storeInbound'])->name('admin.inbound.store');
    // UC-03: Mencatat Stok Keluar (Outbound)
    // Menampilkan halaman form outbound
    Route::get('/outbound', [StockTransactionController::class, 'outboundIndex'])->name('admin.outbound');
    Route::post('/outbound/store', [StockTransactionController::class, 'storeOutbound'])->name('admin.outbound.store');
});

/**
 * Grup Akses: Manajer Pembelian (Procurement Manager)
 * Menangani UC-05, UC-06, UC-07, dan UC-08
 */
Route::middleware(['auth', 'role:procurement_manager'])->prefix('manager')->group(function () {
    // UC-05 & UC-07: Dashboard Monitoring & Notifikasi Stok Menipis
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('manager.dashboard');
    // UC-06: Mengelola Data Supplier
    Route::get('/suppliers', [SupplierController::class, 'index'])->name('manager.suppliers');
    Route::post('/suppliers/store', [SupplierController::class, 'store'])->name('suppliers.store');
    // UC-08: Mengelola Laporan Persediaan
    Route::get('/reports', [InventoryReportController::class, 'index'])->name('manager.reports');
});


Route::post('/logout', function () {
    Auth::logout();
    return redirect('/');
})->name('logout');
