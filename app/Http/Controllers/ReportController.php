<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\Pembelian;
use App\Models\Category;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function dailySales(Request $request)
    {
        $filterDate = $request->input('date', Carbon::today()->toDateString());

        $materialCategoryIds = Category::whereIn('name', ['CAT', 'UMUM'])->pluck('id')->toArray();
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

        return view('reports.daily_sales_categorized', compact('totalDailySales', 'dailySalesMaterial', 'dailySalesKeramik', 'salesToday', 'filterDate'));
    }

    public function monthlySales(Request $request)
    {
        $filterMonth = $request->input('month', Carbon::now()->month);
        $filterYear = $request->input('year', Carbon::now()->year);

        $materialCategoryIds = Category::whereIn('name', ['CAT', 'UMUM'])->pluck('id')->toArray();
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

        return view('reports.monthly_sales_categorized', compact('totalMonthlySales', 'monthlySalesMaterial', 'monthlySalesKeramik', 'salesThisMonth', 'filterMonth', 'filterYear'));
    }

    public function profitLoss(Request $request)
    {
        $filterMonth = $request->input('month', Carbon::now()->month);
        $filterYear = $request->input('year', Carbon::now()->year);

        $materialCategoryIds = Category::whereIn('name', ['CAT', 'UMUM'])->pluck('id')->toArray();
        $keramikCategoryIds = Category::where('name', 'KERAMIK')->pluck('id')->toArray();

        // Total Pendapatan Penjualan per Kategori
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

        // Total Biaya Pembelian per Kategori
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

        // Perkiraan Laba Rugi per Kategori
        $labaRugiMaterial = $penjualanMaterial - $pembelianMaterial;
        $labaRugiKeramik = $penjualanKeramik - $pembelianKeramik;
        $totalLabaRugi = $labaRugiMaterial + $labaRugiKeramik;

        return view('reports.profit_loss_categorized', compact(
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

    public function printDailySales(Request $request)
    {
        $filterDate = $request->input('date', Carbon::today()->toDateString());

        $materialCategoryIds = Category::whereIn('name', ['CAT', 'UMUM'])->pluck('id')->toArray();
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

        $materialCategoryIds = Category::whereIn('name', ['CAT', 'UMUM'])->pluck('id')->toArray();
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

        $materialCategoryIds = Category::whereIn('name', ['CAT', 'UMUM'])->pluck('id')->toArray();
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