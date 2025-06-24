<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\Pembelian;
use App\Models\Category;
use App\Models\SaleReturn;
use Carbon\Carbon;

class ReportController extends Controller
{
   public function dailySales(Request $request)
{
    $filterDate = $request->input('date', Carbon::today()->toDateString());

    // Kategori tetap sama
    $materialCategoryIds = Category::whereIn('name', ['CAT', 'UMUM', 'LUAR'])->pluck('id')->toArray();
    $keramikCategoryIds = Category::where('name', 'KERAMIK')->pluck('id')->toArray();

    // 1. Hitung Penjualan Kotor (Gross Sales)
    $grossSalesMaterial = Sale::whereDate('sale_date', $filterDate)
        ->whereHas('items.item', function ($query) use ($materialCategoryIds) {
            $query->whereIn('category_id', $materialCategoryIds);
        })
        ->sum('grand_total');

    $grossSalesKeramik = Sale::whereDate('sale_date', $filterDate)
        ->whereHas('items.item', function ($query) use ($keramikCategoryIds) {
            $query->whereIn('category_id', $keramikCategoryIds);
        })
        ->sum('grand_total');

    // 2. Hitung Total Retur untuk hari yang sama
    $dailyReturnsMaterial = SaleReturn::whereDate('return_date', $filterDate)
        ->whereHas('items.item', function ($query) use ($materialCategoryIds) {
            $query->whereIn('category_id', $materialCategoryIds);
        })
        ->sum('total_returned_amount');

    $dailyReturnsKeramik = SaleReturn::whereDate('return_date', $filterDate)
        ->whereHas('items.item', function ($query) use ($keramikCategoryIds) {
            $query->whereIn('category_id', $keramikCategoryIds);
        })
        ->sum('total_returned_amount');
    
    $totalDailyReturns = $dailyReturnsMaterial + $dailyReturnsKeramik;

    // 3. Hitung Penjualan Bersih (Net Sales)
    $netSalesMaterial = $grossSalesMaterial - $dailyReturnsMaterial;
    $netSalesKeramik = $grossSalesKeramik - $dailyReturnsKeramik;
    $totalNetDailySales = $netSalesMaterial + $netSalesKeramik;

    // Ambil detail transaksi untuk tabel
    $salesToday = Sale::whereDate('sale_date', $filterDate)->with('items.item.category')->get();

    // Kirim data yang sudah dihitung ke view
    return view('reports.daily_sales_categorized', compact(
        'totalNetDailySales', 
        'netSalesMaterial', 
        'netSalesKeramik', 
        'totalDailyReturns', // <-- Kirim data retur ke view
        'salesToday', 
        'filterDate'
    ));
}

   public function monthlySales(Request $request)
{
    $filterMonth = $request->input('month', Carbon::now()->month);
    $filterYear = $request->input('year', Carbon::now()->year);

    $materialCategoryIds = Category::whereIn('name', ['CAT', 'UMUM', 'LUAR'])->pluck('id')->toArray();
    $keramikCategoryIds = Category::where('name', 'KERAMIK')->pluck('id')->toArray();

    // 1. Hitung Penjualan Kotor (Gross Sales) Bulanan
    $grossSalesMaterial = Sale::whereMonth('sale_date', $filterMonth)
        ->whereYear('sale_date', $filterYear)
        ->whereHas('items.item', function ($query) use ($materialCategoryIds) {
            $query->whereIn('category_id', $materialCategoryIds);
        })
        ->sum('grand_total');

    $grossSalesKeramik = Sale::whereMonth('sale_date', $filterMonth)
        ->whereYear('sale_date', $filterYear)
        ->whereHas('items.item', function ($query) use ($keramikCategoryIds) {
            $query->whereIn('category_id', $keramikCategoryIds);
        })
        ->sum('grand_total');

    // 2. Hitung Total Retur Bulanan
    $monthlyReturnsMaterial = SaleReturn::whereMonth('return_date', $filterMonth)
        ->whereYear('return_date', $filterYear)
        ->whereHas('items.item', function ($query) use ($materialCategoryIds) {
            $query->whereIn('category_id', $materialCategoryIds);
        })
        ->sum('total_returned_amount');

    $monthlyReturnsKeramik = SaleReturn::whereMonth('return_date', $filterMonth)
        ->whereYear('return_date', $filterYear)
        ->whereHas('items.item', function ($query) use ($keramikCategoryIds) {
            $query->whereIn('category_id', $keramikCategoryIds);
        })
        ->sum('total_returned_amount');

    $totalMonthlyReturns = $monthlyReturnsMaterial + $monthlyReturnsKeramik;

    // 3. Hitung Penjualan Bersih (Net Sales)
    $netSalesMaterial = $grossSalesMaterial - $monthlyReturnsMaterial;
    $netSalesKeramik = $grossSalesKeramik - $monthlyReturnsKeramik;
    $totalNetMonthlySales = $netSalesMaterial + $netSalesKeramik;

    // Ambil detail transaksi untuk tabel
    $salesThisMonth = Sale::whereMonth('sale_date', $filterMonth)
        ->whereYear('sale_date', $filterYear)
        ->with('items.item.category')
        ->get();

    return view('reports.monthly_sales_categorized', compact(
        'totalNetMonthlySales', 
        'netSalesMaterial', 
        'netSalesKeramik', 
        'totalMonthlyReturns', 
        'salesThisMonth', 
        'filterMonth', 
        'filterYear'
    ));
}

   public function profitLoss(Request $request)
{
    $filterMonth = $request->input('month', Carbon::now()->month);
    $filterYear = $request->input('year', Carbon::now()->year);

    $materialCategoryIds = Category::whereIn('name', ['CAT', 'UMUM', 'LUAR'])->pluck('id')->toArray();
    $keramikCategoryIds = Category::where('name', 'KERAMIK')->pluck('id')->toArray();

    // 1. Total Pendapatan KOTOR (Gross Sales) per Kategori
    $penjualanMaterial = Sale::whereMonth('sale_date', $filterMonth)
        ->whereYear('sale_date', $filterYear)
        ->whereHas('items.item', function ($query) use ($materialCategoryIds) {
            $query->whereIn('category_id', $materialCategoryIds);
        })
        ->sum('grand_total');

    $penjualanKeramik = Sale::whereMonth('sale_date', $filterMonth)
        ->whereYear('sale_date', $filterYear)
        ->whereHas('items.item', function ($query) use ($keramikCategoryIds) {
            $query->whereIn('category_id', $keramikCategoryIds);
        })
        ->sum('grand_total');

    // 2. Total Retur Penjualan per Kategori
    $returMaterial = SaleReturn::whereMonth('return_date', $filterMonth)
        ->whereYear('return_date', $filterYear)
        ->whereHas('items.item', function ($query) use ($materialCategoryIds) {
            $query->whereIn('category_id', $materialCategoryIds);
        })
        ->sum('total_returned_amount');
    
    $returKeramik = SaleReturn::whereMonth('return_date', $filterMonth)
        ->whereYear('return_date', $filterYear)
        ->whereHas('items.item', function ($query) use ($keramikCategoryIds) {
            $query->whereIn('category_id', $keramikCategoryIds);
        })
        ->sum('total_returned_amount');

    // 3. Total Biaya Pembelian per Kategori (HPP)
    $pembelianMaterial = Pembelian::whereMonth('purchase_date', $filterMonth)
        ->whereYear('purchase_date', $filterYear)
        ->whereHas('items.item', function ($query) use ($materialCategoryIds) {
            $query->whereIn('item_id', function ($subQuery) use ($materialCategoryIds) {
                $subQuery->select('id')->from('items')->whereIn('category_id', $materialCategoryIds);
            });
        })
        ->sum('total_amount');

    $pembelianKeramik = Pembelian::whereMonth('purchase_date', $filterMonth)
        ->whereYear('purchase_date', $filterYear)
        ->whereHas('items.item', function ($query) use ($keramikCategoryIds) {
            $query->whereIn('item_id', function ($subQuery) use ($keramikCategoryIds) {
                $subQuery->select('id')->from('items')->whereIn('category_id', $keramikCategoryIds);
            });
        })
        ->sum('total_amount');
    
    // 4. Perhitungan Laba Rugi Bersih
    $labaRugiMaterial = ($penjualanMaterial - $returMaterial) - $pembelianMaterial;
    $labaRugiKeramik = ($penjualanKeramik - $returKeramik) - $pembelianKeramik;
    $totalLabaRugi = $labaRugiMaterial + $labaRugiKeramik;
    
    // 5. Hitung total untuk kartu ringkasan
    $totalPendapatan = $penjualanMaterial + $penjualanKeramik;
    $totalRetur = $returMaterial + $returKeramik;
    $totalBiayaPembelian = $pembelianMaterial + $pembelianKeramik;

    return view('reports.profit_loss_categorized', compact(
        'filterMonth', 'filterYear',
        'penjualanMaterial', 'penjualanKeramik', 'totalPendapatan',
        'returMaterial', 'returKeramik', 'totalRetur',
        'pembelianMaterial', 'pembelianKeramik', 'totalBiayaPembelian',
        'labaRugiMaterial', 'labaRugiKeramik', 'totalLabaRugi'
    ));
}

    public function printDailySales(Request $request)
    {
        $filterDate = $request->input('date', Carbon::today()->toDateString());

        $materialCategoryIds = Category::whereIn('name', ['CAT','UMUM','LUAR'])->pluck('id')->toArray();
        $keramikCategoryIds = Category::where('name', 'KERAMIK')->pluck('id')->toArray();

        $dailySalesMaterial = Sale::whereDate('sale_date', $filterDate)
            ->whereHas('items.item', function ($query) use ($materialCategoryIds) {
                $query->whereIn('category_id', $materialCategoryIds);
            })
            ->sum('grand_total');

        $dailySalesKeramik = Sale::whereDate('sale_date', $filterDate)
            ->whereHas('items.item', function ($query) use ($keramikCategoryIds) {
                $query->whereIn('category_id', $keramikCategoryIds);
            })
            ->sum('grand_total');

        $totalDailySales = $dailySalesMaterial + $dailySalesKeramik;

        $salesToday = Sale::whereDate('sale_date', $filterDate)->with('items.item.category')->get();

        return view('reports.print.daily_sales', compact('totalDailySales', 'dailySalesMaterial', 'dailySalesKeramik', 'salesToday', 'filterDate'));
    }

    public function printMonthlySales(Request $request)
    {
        $filterMonth = $request->input('month', Carbon::now()->month);
        $filterYear = $request->input('year', Carbon::now()->year);

        $materialCategoryIds = Category::whereIn('name', ['CAT','UMUM','LUAR'])->pluck('id')->toArray();
        $keramikCategoryIds = Category::where('name', 'KERAMIK')->pluck('id')->toArray();

        $monthlySalesMaterial = Sale::whereMonth('sale_date', $filterMonth)
            ->whereYear('sale_date', $filterYear)
            ->whereHas('items.item', function ($query) use ($materialCategoryIds) {
                $query->whereIn('category_id', $materialCategoryIds);
            })
            ->sum('grand_total');

        $monthlySalesKeramik = Sale::whereMonth('sale_date', $filterMonth)
            ->whereYear('sale_date', $filterYear)
            ->whereHas('items.item', function ($query) use ($keramikCategoryIds) {
                $query->whereIn('category_id', $keramikCategoryIds);
            })
            ->sum('grand_total');

        $totalMonthlySales = $monthlySalesMaterial + $monthlySalesKeramik;

        $salesThisMonth = Sale::whereMonth('sale_date', $filterMonth)
            ->whereYear('sale_date', $filterYear)
            ->with('items.item.category')
            ->get();

        return view('reports.print.monthly_sales', compact('totalMonthlySales', 'monthlySalesMaterial', 'monthlySalesKeramik', 'salesThisMonth', 'filterMonth', 'filterYear'));
    }

    public function printProfitLoss(Request $request)
    {
        $filterMonth = $request->input('month', Carbon::now()->month);
        $filterYear = $request->input('year', Carbon::now()->year);

        $materialCategoryIds = Category::whereIn('name', ['CAT','UMUM','LUAR'])->pluck('id')->toArray();
        $keramikCategoryIds = Category::where('name', 'KERAMIK')->pluck('id')->toArray();

        $penjualanMaterial = Sale::whereMonth('sale_date', $filterMonth)
            ->whereYear('sale_date', $filterYear)
            ->whereHas('items.item', function ($query) use ($materialCategoryIds) {
                $query->whereIn('category_id', $materialCategoryIds);
            })
            ->sum('grand_total');

        $penjualanKeramik = Sale::whereMonth('sale_date', $filterMonth)
            ->whereYear('sale_date', $filterYear)
            ->whereHas('items.item', function ($query) use ($keramikCategoryIds) {
                $query->whereIn('category_id', $keramikCategoryIds);
            })
            ->sum('grand_total');

        $totalPendapatan = $penjualanMaterial + $penjualanKeramik;

        $pembelianMaterial = Pembelian::whereMonth('purchase_date', $filterMonth)
            ->whereYear('purchase_date', $filterYear)
            ->whereHas('items.item', function ($query) use ($materialCategoryIds) {
                $query->whereIn('item_id', function ($subQuery) use ($materialCategoryIds) {
                    $subQuery->select('id')->from('items')->whereIn('category_id', $materialCategoryIds);
                });
            })
            ->sum('total_amount');

        $pembelianKeramik = Pembelian::whereMonth('purchase_date', $filterMonth)
            ->whereYear('purchase_date', $filterYear)
            ->whereHas('items.item', function ($query) use ($keramikCategoryIds) {
                $query->whereIn('item_id', function ($subQuery) use ($keramikCategoryIds) {
                    $subQuery->select('id')->from('items')->whereIn('category_id', $keramikCategoryIds);
                });
            })
            ->sum('total_amount');

        $totalBiayaPembelian = $pembelianMaterial + $pembelianKeramik;

        $labaRugiMaterial = $penjualanMaterial - $pembelianMaterial;
        $labaRugiKeramik = $penjualanKeramik - $pembelianKeramik;
        $totalLabaRugi = $labaRugiMaterial + $labaRugiKeramik;

        return view('reports.print.profit_loss', compact(
            'filterMonth',
            'filterYear',
            'penjualanMaterial',
            'penjualanKeramik',
            'totalPendapatan',
            'pembelianMaterial',
            'pembelianKeramik',
            'totalBiayaPembelian',
            'labaRugiMaterial',
            'labaRugiKeramik',
            'totalLabaRugi'
        ));
    }
}