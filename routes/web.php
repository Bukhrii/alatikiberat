<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InventoryReportController;
use App\Http\Controllers\SparePartController;
use App\Http\Controllers\StockTransactionController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\InventoryController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OutbondController;

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
    Route::put('/spare-parts/{id}', [SparePartController::class, 'update'])->name('spare-parts.update');
    Route::delete('/spare-parts/{id}', [SparePartController::class, 'destroy'])->name('spare-parts.destroy');
    // UC-04: Melihat & Update Lokasi Rak
    Route::get('/locations', [SparePartController::class, 'rackLocations'])->name('admin.locations');
    Route::get('/admin/lokasi-stok', [InventoryController::class, 'locationIndex'])->name('admin.location');
    Route::put('/locations/move/{id}', [SparePartController::class, 'moveItem'])->name('admin.locations.move');
    // UC-02: Mencatat Stok Masuk (Inbound)
    Route::get('/inbound', [StockTransactionController::class, 'inboundIndex'])->name('admin.inbound');
    Route::post('/inbound/store', [StockTransactionController::class, 'storeInbound'])->name('admin.inbound.store');
    Route::patch('/admin/inbound/{id}/status', [StockTransactionController::class, 'updateStatus'])->name('admin.inbound.updateStatus');
    // UC-03: Mencatat Stok Keluar (Outbond)
    // 1. Dashboard Utama Outbound (Gunakan OutbondController agar statistik muncul)
    Route::get('/admin/outbound', [OutbondController::class, 'index'])->name('admin.outbound');
    // Menampilkan halaman form outbound
    // 2. Proses Simpan Transaksi (Tetap di StockTransactionController)
    Route::post('/admin/outbound/store', [StockTransactionController::class, 'storeOutbound'])->name('admin.outbound.store');
    Route::patch('/transactions/{id}/status', [StockTransactionController::class, 'updateStatus'])->name('admin.transactions.updateStatus');
});

/**
 * Grup Akses: Manajer Pembelian (Procurement Manager)
 * Menangani UC-05, UC-06, UC-07, dan UC-08
 */
Route::middleware(['auth', 'role:procurement_manager'])->prefix('manager')->group(function () {
    // UC-05 & UC-07: Dashboard Monitoring & Notifikasi Stok Menipis
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('manager.dashboard');
    Route::post('/mass-reorder', [DashboardController::class, 'massReorder'])->name('manager.mass-reorder');
    Route::post('/reorder/{id}', [DashboardController::class, 'singleReorder'])->name('manager.reorder');
    // UC-06: Mengelola Data Supplier
    Route::get('/supplier-management', [SupplierController::class, 'index'])->name('manager.suppliers');
    Route::post('/suppliers/store', [SupplierController::class, 'store'])->name('suppliers.store');
    Route::put('/suppliers/update/{id}', [SupplierController::class, 'update'])->name('suppliers.update');
    Route::delete('/suppliers/delete/{id}', [SupplierController::class, 'destroy'])->name('suppliers.destroy');
    // UC-08: Mengelola Laporan Persediaan
    Route::get('/reports', [InventoryReportController::class, 'index'])->name('manager.reports');
    Route::get('/reports/export', [InventoryReportController::class, 'exportExcel'])->name('manager.reports.export');
    Route::get('/reports/export-pdf', [InventoryReportController::class, 'exportPdf'])->name('manager.reports.export-pdf');
});


Route::post('/logout', function () {
    Auth::logout();
    return redirect('/');
})->name('logout');
