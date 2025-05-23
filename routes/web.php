<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\KeramikController;
use App\Http\Controllers\CatController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\SaleReturnController;
use App\Http\Controllers\PembelianController;
use App\Http\Controllers\ReturPembelianController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\Settings\UserController; // Tambahkan use statement untuk UserController
use App\Http\Controllers\Settings\CompanyController; // Pastikan ini benar

Route::get('/', function () {
    return view('auth.login');
});

// Auth routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected routes - Semua rute di dalam group ini memerlukan otentikasi
Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', function () {
        return view('dashboard.index');
    })->name('dashboard');

    Route::prefix('inventory')->name('inventory.')->group(function () {
        // Routes for Categories (Jenis Barang)
        Route::get('categories', [CategoryController::class, 'index'])->name('categories');
        Route::get('categories/create', [CategoryController::class, 'create'])->name('categories.create');
        Route::post('categories', [CategoryController::class, 'store'])->name('categories.store');
        Route::get('categories/{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
        Route::put('categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
        Route::delete('categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');

        // Routes for generic Items (Daftar Barang)
        Route::get('items', [ItemController::class, 'index'])->name('items');
        Route::get('items/create', [ItemController::class, 'create'])->name('items.create');
        Route::post('items', [ItemController::class, 'store'])->name('items.store');
        Route::get('items/{item}', [ItemController::class, 'show'])->name('items.show');
        Route::get('items/{item}/edit', [ItemController::class, 'edit'])->name('items.edit');
        Route::put('items/{item}', [ItemController::class, 'update'])->name('items.update');
        Route::delete('items/{item}', [ItemController::class, 'destroy'])->name('items.destroy');

        // Routes for Keramik
        Route::get('keramiks', [KeramikController::class, 'index'])->name('keramiks');
        Route::get('keramiks/create', [KeramikController::class, 'create'])->name('keramiks.create');
        Route::post('keramiks', [KeramikController::class, 'store'])->name('keramiks.store');
        Route::get('keramiks/{keramik}', [KeramikController::class, 'show'])->name('keramiks.show');
        Route::get('keramiks/{keramik}/edit', [KeramikController::class, 'edit'])->name('keramiks.edit');
        Route::put('keramiks/{keramik}', [KeramikController::class, 'update'])->name('keramiks.update');
        Route::delete('keramiks/{keramik}', [KeramikController::class, 'destroy'])->name('keramiks.destroy');

        // Routes for Cat
        Route::get('cats', [CatController::class, 'index'])->name('cats');
        Route::get('cats/create', [CatController::class, 'create'])->name('cats.create');
        Route::post('cats', [CatController::class, 'store'])->name('cats.store');
        Route::get('cats/{cat}', [CatController::class, 'show'])->name('cats.show');
        Route::get('cats/{cat}/edit', [CatController::class, 'edit'])->name('cats.edit');
        Route::put('cats/{cat}', [CatController::class, 'update'])->name('cats.update');
        Route::delete('cats/{cat}', [CatController::class, 'destroy'])->name('cats.destroy');

    }); // Tutup Route::prefix('inventory')
   // Grup Rute untuk "Penjualan"
    Route::prefix('penjualan')->name('penjualan.')->group(function () {
        Route::resource('transaksi', SaleController::class);
        Route::get('/penjualan/transaksi/{sale}', [SaleController::class, 'show'])->name('penjualan.transaksi.show');
        // Resource Route untuk Transaksi Penjualan
        // Ini akan menghasilkan:
        // GET|HEAD /penjualan/transaksi                     -> penjualan.transaksi.index
        // POST     /penjualan/transaksi                     -> penjualan.transaksi.store
        // GET|HEAD /penjualan/transaksi/create                -> penjualan.transaksi.create
        // GET|HEAD /penjualan/transaksi/{transaksi}          -> penjualan.transaksi.show
        // PUT|PATCH /penjualan/transaksi/{transaksi}          -> penjualan.transaksi.update
        // DELETE   /penjualan/transaksi/{transaksi}          -> penjualan.transaksi.destroy
        // GET|HEAD /penjualan/transaksi/{transaksi}/edit     -> penjualan.transaksi.edit

        Route::resource('retur', SaleReturnController::class);
        // Route::get('/penjualan/retur/{saleReturn}', [SaleReturnController::class, 'show'])->name('penjualan.retur.show');
        // Route::delete('/penjualan/retur/{saleReturn}', [SaleReturnController::class, 'destroy'])->name('penjualan.retur.destroy');
        // Resource Route untuk Retur Penjualan
        // Ini akan menghasilkan:
        // GET|HEAD /penjualan/retur                           -> penjualan.retur.index
        // POST     /penjualan/retur                           -> penjualan.retur.store
        // GET|HEAD /penjualan/retur/create                      -> penjualan.retur.create
        // GET|HEAD /penjualan/retur/{retur}                    -> penjualan.retur.show
        // PUT|PATCH /penjualan/retur/{retur}                    -> penjualan.retur.update
        // DELETE   /penjualan/retur/{retur}                    -> penjualan.retur.destroy
        // GET|HEAD /penjualan/retur/{retur}/edit               -> penjualan.retur.edit
        Route::get('penjualan/transaksi/{sale}/print', [SaleController::class, 'printReceipt'])->name('transaksi.print_receipt');
    }); // Akhir dari grup prefix 'penjualan'
    Route::resource('pembelian', PembelianController::class);
    Route::resource('retur-pembelian', ReturPembelianController::class)->except(['edit', 'update']);
    Route::get('/pembelian/{pembelian}/items', [ReturPembelianController::class, 'getPembelianItems']);

    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/daily-sales', [ReportController::class, 'dailySales'])->name('daily-sales');
        Route::get('/monthly-sales', [ReportController::class, 'monthlySales'])->name('monthly-sales');
        Route::get('/profit-loss', [ReportController::class, 'profitLoss'])->name('profit-loss');

        Route::get('/print/daily-sales', [ReportController::class, 'printDailySales'])->name('print-daily-sales');
        Route::get('/print/monthly-sales', [ReportController::class, 'printMonthlySales'])->name('print-monthly-sales');
        Route::get('/print/profit-loss', [ReportController::class, 'printProfitLoss'])->name('print-profit-loss');
    });
    Route::prefix('settings')->name('settings.')->group(function () {
    // Manajemen User Routes
    Route::resource('users', UserController::class);

    // Profil Perusahaan Routes
    Route::prefix('company')->name('company.')->group(function () {
        Route::get('/edit', [CompanyController::class, 'edit'])->name('edit');
        Route::put('/update', [CompanyController::class, 'update'])->name('update');
    });
});
}); // Tutup Route::middleware(['auth'])