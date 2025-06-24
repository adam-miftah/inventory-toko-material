<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\KeramikController;
use App\Http\Controllers\CatController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\SaleReturnController;
use App\Http\Controllers\PembelianController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\ReturPembelianController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\Settings\UserController;
use App\Http\Controllers\Settings\CompanyController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Halaman Awal & Rute Otentikasi
Route::get('/', fn() => view('auth.login'))->middleware('guest');
Route::get('/login', [AuthController::class, 'showLogin'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->middleware('guest');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Rute yang Dilindungi Otentikasi
Route::middleware(['auth'])->group(function () {

    // MENJADI INI:
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // --- MODUL INVENTORY ---
    Route::prefix('inventory')->name('inventory.')->group(function () {
        // Menggunakan Route::resource untuk standarisasi rute CRUD
        Route::resource('items', ItemController::class);
        Route::resource('categories', CategoryController::class);
        
        // Rute import dipindahkan ke sini agar terlindungi auth
        Route::post('items/import', [ItemController::class, 'import'])->name('items.import');

        // Catatan: Controller terpisah untuk Cat & Keramik kurang ideal,
        // namun untuk saat ini kita sederhanakan definisinya.
        Route::resource('cats', CatController::class);
        Route::resource('keramiks', KeramikController::class);
    });

    // --- MODUL PENJUALAN ---
    Route::prefix('penjualan')->name('penjualan.')->group(function () {
        Route::get('transaksi/{transaksi}/print', [SaleController::class, 'printReceipt'])->name('transaksi.print_receipt');
        Route::resource('transaksi', SaleController::class);
        Route::resource('retur', SaleReturnController::class);
    });

    // --- MODUL PEMBELIAN ---
    // Menggunakan resource controller untuk Pembelian dan Supplier
    Route::resource('pembelian', PembelianController::class);
    Route::resource('suppliers', SupplierController::class);
    
    // Rute untuk Retur Pembelian
    Route::get('/pembelian/{pembelian}/items', [ReturPembelianController::class, 'getPembelianItems']);
    Route::resource('retur-pembelian', ReturPembelianController::class)->except(['edit', 'update']);

    // --- MODUL LAPORAN ---
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/daily-sales', [ReportController::class, 'dailySales'])->name('daily-sales');
        Route::get('/monthly-sales', [ReportController::class, 'monthlySales'])->name('monthly-sales');
        Route::get('/profit-loss', [ReportController::class, 'profitLoss'])->name('profit-loss');
        
        // Rute untuk mencetak laporan
        Route::get('/print/daily-sales', [ReportController::class, 'printDailySales'])->name('print-daily-sales');
        Route::get('/print/monthly-sales', [ReportController::class, 'printMonthlySales'])->name('print-monthly-sales');
        Route::get('/print/profit-loss', [ReportController::class, 'printProfitLoss'])->name('print-profit-loss');
    });

    // --- MODUL PENGATURAN ---
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::resource('users', UserController::class);
        Route::get('/company', [CompanyController::class, 'index'])->name('company.index');
        Route::get('/company/edit', [CompanyController::class, 'edit'])->name('company.edit');
        Route::put('/company/update', [CompanyController::class, 'update'])->name('company.update');
    });
});