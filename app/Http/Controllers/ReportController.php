<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\Pembelian;
use App\Models\Category;
use App\Models\SaleReturn;
use App\Models\ReturPembelian;
use App\Models\SaleItem;
use App\Models\SaleReturnItem;
use App\Models\PembelianItem;
use App\Models\ReturPembelianItem;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    /**
     * Mendapatkan ID kategori berdasarkan tipe.
     */
    private function getCategoryIdsByType(): array
    {
        return [
            'material' => Category::whereIn('type', ['general', 'cat', 'luar'])->pluck('id'),
            'keramik' => Category::where('type', 'keramik')->pluck('id'),
        ];
    }

    public function dailySales(Request $request)
    {
        $filterDate = $request->input('date', today()->toDateString());
        $categoryIds = $this->getCategoryIdsByType();

        // Penjualan Kotor (menggunakan sale_date)
        $grossSalesMaterial = SaleItem::whereHas('sale', fn($q) => $q->whereDate('sale_date', $filterDate))
            ->whereHas('item', fn($q) => $q->whereIn('category_id', $categoryIds['material']))
            ->sum('subtotal');
        $grossSalesKeramik = SaleItem::whereHas('sale', fn($q) => $q->whereDate('sale_date', $filterDate))
            ->whereHas('item', fn($q) => $q->whereIn('category_id', $categoryIds['keramik']))
            ->sum('subtotal');

        // Retur Penjualan (menggunakan return_date DENGAN 'n')
        $dailyReturnsMaterial = SaleReturnItem::whereHas('saleReturn', fn($q) => $q->whereDate('return_date', $filterDate))
            ->whereHas('item', fn($q) => $q->whereIn('category_id', $categoryIds['material']))
            ->sum('subtotal');
        $dailyReturnsKeramik = SaleReturnItem::whereHas('saleReturn', fn($q) => $q->whereDate('return_date', $filterDate))
            ->whereHas('item', fn($q) => $q->whereIn('category_id', $categoryIds['keramik']))
            ->sum('subtotal');

        $totalDailyReturns = $dailyReturnsMaterial + $dailyReturnsKeramik;
        $netSalesMaterial = $grossSalesMaterial - $dailyReturnsMaterial;
        $netSalesKeramik = $grossSalesKeramik - $dailyReturnsKeramik;
        $totalNetDailySales = $netSalesMaterial + $netSalesKeramik;

        $salesToday = Sale::whereDate('sale_date', $filterDate)->with(['user', 'items.item.category'])->get();

        return view('reports.daily_sales_categorized', compact(
            'netSalesMaterial', 'netSalesKeramik', 'totalDailyReturns', 'totalNetDailySales', 'salesToday', 'filterDate'
        ));
    }

    public function monthlySales(Request $request)
    {
        $filterMonth = $request->input('month', now()->month);
        $filterYear = $request->input('year', now()->year);
        $categoryIds = $this->getCategoryIdsByType();
        
        // Penjualan Kotor
        $grossSalesMaterial = SaleItem::whereHas('sale', fn($q) => $q->whereMonth('sale_date', $filterMonth)->whereYear('sale_date', $filterYear))
            ->whereHas('item', fn($q) => $q->whereIn('category_id', $categoryIds['material']))->sum('subtotal');
        $grossSalesKeramik = SaleItem::whereHas('sale', fn($q) => $q->whereMonth('sale_date', $filterMonth)->whereYear('sale_date', $filterYear))
            ->whereHas('item', fn($q) => $q->whereIn('category_id', $categoryIds['keramik']))->sum('subtotal');

        // Retur Penjualan (menggunakan return_date DENGAN 'n')
        $monthlyReturnsMaterial = SaleReturnItem::whereHas('saleReturn', fn($q) => $q->whereMonth('return_date', $filterMonth)->whereYear('return_date', $filterYear))
            ->whereHas('item', fn($q) => $q->whereIn('category_id', $categoryIds['material']))->sum('subtotal');
        $monthlyReturnsKeramik = SaleReturnItem::whereHas('saleReturn', fn($q) => $q->whereMonth('return_date', $filterMonth)->whereYear('return_date', $filterYear))
            ->whereHas('item', fn($q) => $q->whereIn('category_id', $categoryIds['keramik']))->sum('subtotal');

        $totalMonthlyReturns = $monthlyReturnsMaterial + $monthlyReturnsKeramik;
        $netSalesMaterial = $grossSalesMaterial - $monthlyReturnsMaterial;
        $netSalesKeramik = $grossSalesKeramik - $monthlyReturnsKeramik;
        $totalNetMonthlySales = $netSalesMaterial + $netSalesKeramik;

        $salesThisMonth = Sale::whereMonth('sale_date', $filterMonth)->whereYear('sale_date', $filterYear)->with(['user', 'items.item.category'])->get();

        return view('reports.monthly_sales_categorized', compact(
            'netSalesMaterial', 'netSalesKeramik', 'totalMonthlyReturns', 'totalNetMonthlySales', 'salesThisMonth', 'filterMonth', 'filterYear'
        ));
    }

    public function profitLoss(Request $request)
    {
        $filterMonth = $request->input('month', now()->month);
        $filterYear = $request->input('year', now()->year);
        $categoryIds = $this->getCategoryIdsByType();

        // Pendapatan
        $penjualanMaterial = SaleItem::whereHas('sale', fn($q) => $q->whereMonth('sale_date', $filterMonth)->whereYear('sale_date', $filterYear))
            ->whereHas('item', fn($q) => $q->whereIn('category_id', $categoryIds['material']))->sum('subtotal');
        $penjualanKeramik = SaleItem::whereHas('sale', fn($q) => $q->whereMonth('sale_date', $filterMonth)->whereYear('sale_date', $filterYear))
            ->whereHas('item', fn($q) => $q->whereIn('category_id', $categoryIds['keramik']))->sum('subtotal');

        // Pengurang Pendapatan (Retur Penjualan -> tabel sale_returns -> kolom 'return_date')
        $returMaterial = SaleReturnItem::whereHas('saleReturn', fn($q) => $q->whereMonth('return_date', $filterMonth)->whereYear('return_date', $filterYear))
            ->whereHas('item', fn($q) => $q->whereIn('category_id', $categoryIds['material']))->sum('subtotal');
        $returKeramik = SaleReturnItem::whereHas('saleReturn', fn($q) => $q->whereMonth('return_date', $filterMonth)->whereYear('return_date', $filterYear))
            ->whereHas('item', fn($q) => $q->whereIn('category_id', $categoryIds['keramik']))->sum('subtotal');
        
        // Biaya Pokok Penjualan (Pembelian)
        $pembelianMaterial = PembelianItem::whereHas('pembelian', fn($q) => $q->whereMonth('purchase_date', $filterMonth)->whereYear('purchase_date', $filterYear))
            ->whereHas('item', fn($q) => $q->whereIn('category_id', $categoryIds['material']))->sum('subtotal');
        $pembelianKeramik = PembelianItem::whereHas('pembelian', fn($q) => $q->whereMonth('purchase_date', $filterMonth)->whereYear('purchase_date', $filterYear))
            ->whereHas('item', fn($q) => $q->whereIn('category_id', $categoryIds['keramik']))->sum('subtotal');

        // Pengurang Biaya (Retur Pembelian -> tabel retur_pembelians -> kolom 'retur_date')
        $returPembelianMaterial = ReturPembelianItem::whereHas('returPembelian', fn($q) => $q->whereMonth('retur_date', $filterMonth)->whereYear('retur_date', $filterYear))
            ->whereHas('item', fn($q) => $q->whereIn('category_id', $categoryIds['material']))->sum('subtotal_returned');
        $returPembelianKeramik = ReturPembelianItem::whereHas('returPembelian', fn($q) => $q->whereMonth('retur_date', $filterMonth)->whereYear('retur_date', $filterYear))
            ->whereHas('item', fn($q) => $q->whereIn('category_id', $categoryIds['keramik']))->sum('subtotal_returned');

        // Perhitungan Laba Rugi
        $hppBersihMaterial = $pembelianMaterial - $returPembelianMaterial;
        $hppBersihKeramik = $pembelianKeramik - $returPembelianKeramik;
        $labaRugiMaterial = ($penjualanMaterial - $returMaterial) - $hppBersihMaterial;
        $labaRugiKeramik = ($penjualanKeramik - $returKeramik) - $hppBersihKeramik;
        
        // Total
        $totalPendapatan = $penjualanMaterial + $penjualanKeramik;
        $totalRetur = $returMaterial + $returKeramik;
        $totalBiayaPembelian = $pembelianMaterial + $pembelianKeramik;
        $totalLabaRugi = $labaRugiMaterial + $labaRugiKeramik;

        return view('reports.profit_loss_categorized', compact(
            'filterMonth', 'filterYear', 'penjualanMaterial', 'penjualanKeramik', 'totalPendapatan',
            'returMaterial', 'returKeramik', 'totalRetur', 'pembelianMaterial', 'pembelianKeramik',
            'totalBiayaPembelian', 'labaRugiMaterial', 'labaRugiKeramik', 'totalLabaRugi'
        ));
    }

public function printDailySales(Request $request)
    {
        // LOGIKA DISAMAKAN DENGAN dailySales UNTUK KONSISTENSI DATA
        $filterDate = $request->input('date', today()->toDateString());
        $categoryIds = $this->getCategoryIdsByType();

        $grossSalesMaterial = SaleItem::whereHas('sale', fn($q) => $q->whereDate('sale_date', $filterDate))
            ->whereHas('item', fn($q) => $q->whereIn('category_id', $categoryIds['material']))->sum('subtotal');
        $grossSalesKeramik = SaleItem::whereHas('sale', fn($q) => $q->whereDate('sale_date', $filterDate))
            ->whereHas('item', fn($q) => $q->whereIn('category_id', $categoryIds['keramik']))->sum('subtotal');

        $dailyReturnsMaterial = SaleReturnItem::whereHas('saleReturn', fn($q) => $q->whereDate('return_date', $filterDate))
            ->whereHas('item', fn($q) => $q->whereIn('category_id', $categoryIds['material']))->sum('subtotal');
        $dailyReturnsKeramik = SaleReturnItem::whereHas('saleReturn', fn($q) => $q->whereDate('return_date', $filterDate))
            ->whereHas('item', fn($q) => $q->whereIn('category_id', $categoryIds['keramik']))->sum('subtotal');

        $totalDailyReturns = $dailyReturnsMaterial + $dailyReturnsKeramik;
        $netSalesMaterial = $grossSalesMaterial - $dailyReturnsMaterial;
        $netSalesKeramik = $grossSalesKeramik - $dailyReturnsKeramik;
        $totalNetDailySales = $netSalesMaterial + $netSalesKeramik;

        $salesToday = Sale::whereDate('sale_date', $filterDate)->with(['user', 'items.item'])->latest()->get();

        $data = compact('netSalesMaterial', 'netSalesKeramik', 'totalDailyReturns', 'totalNetDailySales', 'salesToday', 'filterDate');
        
        $pdf = Pdf::loadView('reports.print.daily_sales', $data);
        return $pdf->stream('laporan-penjualan-harian-' . $filterDate . '.pdf');
    }

    public function printMonthlySales(Request $request)
    {
        // LOGIKA DISAMAKAN DENGAN monthlySales UNTUK KONSISTENSI DATA
        $filterMonth = $request->input('month', now()->month);
        $filterYear = $request->input('year', now()->year);
        $categoryIds = $this->getCategoryIdsByType();
        
        $grossSalesMaterial = SaleItem::whereHas('sale', fn($q) => $q->whereMonth('sale_date', $filterMonth)->whereYear('sale_date', $filterYear))
            ->whereHas('item', fn($q) => $q->whereIn('category_id', $categoryIds['material']))->sum('subtotal');
        $grossSalesKeramik = SaleItem::whereHas('sale', fn($q) => $q->whereMonth('sale_date', $filterMonth)->whereYear('sale_date', $filterYear))
            ->whereHas('item', fn($q) => $q->whereIn('category_id', $categoryIds['keramik']))->sum('subtotal');

        $monthlyReturnsMaterial = SaleReturnItem::whereHas('saleReturn', fn($q) => $q->whereMonth('return_date', $filterMonth)->whereYear('return_date', $filterYear))
            ->whereHas('item', fn($q) => $q->whereIn('category_id', $categoryIds['material']))->sum('subtotal');
        $monthlyReturnsKeramik = SaleReturnItem::whereHas('saleReturn', fn($q) => $q->whereMonth('return_date', $filterMonth)->whereYear('return_date', $filterYear))
            ->whereHas('item', fn($q) => $q->whereIn('category_id', $categoryIds['keramik']))->sum('subtotal');

        $totalMonthlyReturns = $monthlyReturnsMaterial + $monthlyReturnsKeramik;
        $netSalesMaterial = $grossSalesMaterial - $monthlyReturnsMaterial;
        $netSalesKeramik = $grossSalesKeramik - $monthlyReturnsKeramik;
        $totalNetMonthlySales = $netSalesMaterial + $netSalesKeramik;

        $salesThisMonth = Sale::whereMonth('sale_date', $filterMonth)->whereYear('sale_date', $filterYear)->with(['user', 'items.item'])->latest()->get();

        $data = compact('netSalesMaterial', 'netSalesKeramik', 'totalMonthlyReturns', 'totalNetMonthlySales', 'salesThisMonth', 'filterMonth', 'filterYear');

        $pdf = Pdf::loadView('reports.print.monthly_sales', $data);
        return $pdf->stream('laporan-penjualan-bulanan-' . $filterYear . '-' . $filterMonth . '.pdf');
    }

    public function printProfitLoss(Request $request)
    {
        // LOGIKA DISAMAKAN DENGAN profitLoss UNTUK KONSISTENSI DATA
        $filterMonth = $request->input('month', now()->month);
        $filterYear = $request->input('year', now()->year);
        $categoryIds = $this->getCategoryIdsByType();

        $penjualanKotorMaterial = SaleItem::whereHas('sale', fn($q) => $q->whereMonth('sale_date', $filterMonth)->whereYear('sale_date', $filterYear))
            ->whereHas('item', fn($q) => $q->whereIn('category_id', $categoryIds['material']))->sum('subtotal');
        $penjualanKotorKeramik = SaleItem::whereHas('sale', fn($q) => $q->whereMonth('sale_date', $filterMonth)->whereYear('sale_date', $filterYear))
            ->whereHas('item', fn($q) => $q->whereIn('category_id', $categoryIds['keramik']))->sum('subtotal');
        
        $returPenjualanMaterial = SaleReturnItem::whereHas('saleReturn', fn($q) => $q->whereMonth('return_date', $filterMonth)->whereYear('return_date', $filterYear))
            ->whereHas('item', fn($q) => $q->whereIn('category_id', $categoryIds['material']))->sum('subtotal');
        $returPenjualanKeramik = SaleReturnItem::whereHas('saleReturn', fn($q) => $q->whereMonth('return_date', $filterMonth)->whereYear('return_date', $filterYear))
            ->whereHas('item', fn($q) => $q->whereIn('category_id', $categoryIds['keramik']))->sum('subtotal');

        $penjualanBersihMaterial = $penjualanKotorMaterial - $returPenjualanMaterial;
        $penjualanBersihKeramik = $penjualanKotorKeramik - $returPenjualanKeramik;

        $pembelianKotorMaterial = PembelianItem::whereHas('pembelian', fn($q) => $q->whereMonth('purchase_date', $filterMonth)->whereYear('purchase_date', $filterYear))
            ->whereHas('item', fn($q) => $q->whereIn('category_id', $categoryIds['material']))->sum('subtotal');
        $pembelianKotorKeramik = PembelianItem::whereHas('pembelian', fn($q) => $q->whereMonth('purchase_date', $filterMonth)->whereYear('purchase_date', $filterYear))
            ->whereHas('item', fn($q) => $q->whereIn('category_id', $categoryIds['keramik']))->sum('subtotal');
        
        $returPembelianMaterial = ReturPembelianItem::whereHas('returPembelian', fn($q) => $q->whereMonth('retur_date', $filterMonth)->whereYear('retur_date', $filterYear))
            ->whereHas('item', fn($q) => $q->whereIn('category_id', $categoryIds['material']))->sum('subtotal_returned');
        $returPembelianKeramik = ReturPembelianItem::whereHas('returPembelian', fn($q) => $q->whereMonth('retur_date', $filterMonth)->whereYear('retur_date', $filterYear))
            ->whereHas('item', fn($q) => $q->whereIn('category_id', $categoryIds['keramik']))->sum('subtotal_returned');

        $pembelianBersihMaterial = $pembelianKotorMaterial - $returPembelianMaterial;
        $pembelianBersihKeramik = $pembelianKotorKeramik - $returPembelianKeramik;

        $labaKotorMaterial = $penjualanBersihMaterial - $pembelianBersihMaterial;
        $labaKotorKeramik = $penjualanBersihKeramik - $pembelianBersihKeramik;
        
        $totalPendapatanBersih = $penjualanBersihMaterial + $penjualanBersihKeramik;
        $totalHpp = $pembelianBersihMaterial + $pembelianBersihKeramik;
        $totalLabaKotor = $labaKotorMaterial + $labaKotorKeramik;

        $data = compact(
        'filterMonth', 'filterYear',
        'penjualanKotorMaterial', 'penjualanKotorKeramik',
        'returPenjualanMaterial', 'returPenjualanKeramik',
        'penjualanBersihMaterial', 'penjualanBersihKeramik', 'totalPendapatanBersih',
        'pembelianKotorMaterial', 'pembelianKotorKeramik',
        'returPembelianMaterial', 'returPembelianKeramik',
        'pembelianBersihMaterial', 'pembelianBersihKeramik', 'totalHpp',
        'labaKotorMaterial', 'labaKotorKeramik', 'totalLabaKotor'
    );

    $pdf = Pdf::loadView('reports.print.profit_loss', $data);
    return $pdf->stream('laporan-laba-rugi-' . $filterYear . '-' . $filterMonth . '.pdf');
}
}