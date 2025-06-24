<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Item;
use App\Models\Pembelian;
use App\Models\Sale;
use App\Models\SaleReturn; // <-- 1. Import model SaleReturn
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // === 1. Data untuk Kartu Statistik Utama (KPI Cards) ===

        // Penjualan & Retur Hari Ini
        $todaySales = Sale::whereDate('sale_date', today())->sum('grand_total');
        $todayReturns = SaleReturn::whereDate('return_date', today())->sum('total_returned_amount'); // <-- Hitung retur hari ini
        $netTodaySales = $todaySales - $todayReturns; // <-- Hitung penjualan bersih
        $todayTransactionsCount = Sale::whereDate('sale_date', today())->count();

        // Pembelian Bulan Ini
        $thisMonthPurchases = Pembelian::whereMonth('purchase_date', now()->month)
                                      ->whereYear('purchase_date', now()->year)
                                      ->sum('total_amount');
        $thisMonthPurchasesCount = Pembelian::whereMonth('purchase_date', now()->month)
                                           ->whereYear('purchase_date', now()->year)
                                           ->count();

        // Total Produk & Kategori
        $totalItems = Item::count();
        $totalCategories = Category::count();


        // === 2. Data untuk Grafik (Charts) ===

        // Grafik Penjualan Bersih 7 Hari Terakhir
        $salesData = [];
        $salesLabels = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $salesLabels[] = $date->isoFormat('dd, DD MMM');
            // Kalkulasi penjualan bersih untuk setiap hari di grafik
            $dailyGrossSales = Sale::whereDate('sale_date', $date)->sum('grand_total');
            $dailyTotalReturns = SaleReturn::whereDate('return_date', $date)->sum('total_returned_amount');
            $salesData[] = $dailyGrossSales - $dailyTotalReturns;
        }

        // Grafik Distribusi Stok
        $categoryDistribution = Category::withCount('items')
            ->get()
            ->mapWithKeys(function ($category) {
                $totalStock = $category->items->sum('stock');
                return [$category->name => $totalStock];
            });

        $totalStockAll = $categoryDistribution->sum();
        $categoryDistribution = $categoryDistribution->map(function ($stock) use ($totalStockAll) {
            return $totalStockAll > 0 ? ($stock / $totalStockAll) * 100 : 0;
        });
        
        $categoryColors = ['#0d6efd', '#fd7e14', '#198754', '#6c757d', '#dc3545', '#ffc107', '#0dcaf0'];


        // === 3. Data untuk Tabel Informasi ===
        
        // Stok Hampir Habis
        $lowStockItems = Item::with('category')->where('stock', '<=', 10)->orderBy('stock', 'asc')->take(5)->get();

        // Transaksi Terakhir
        $recentTransactions = Sale::with('items')->latest()->take(5)->get();

        // === 4. Kirim semua data ke view ===
        return view('dashboard.index', compact(
            'netTodaySales',
            'todayTransactionsCount',
            'todayReturns', // <-- Kirim data retur
            'thisMonthPurchases',
            'thisMonthPurchasesCount',
            'totalItems',
            'totalCategories',
            'lowStockItems',
            'recentTransactions',
            'salesData',
            'salesLabels',
            'categoryDistribution',
            'categoryColors'
        ));
    }
}
