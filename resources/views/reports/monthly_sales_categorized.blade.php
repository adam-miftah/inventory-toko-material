@extends('layouts.app')

@section('title', 'Laporan Penjualan Bulanan per Kategori')

@section('content')
  <div class="container-fluid">
    <h4 class="mb-4">Laporan Penjualan Bulanan per Kategori</h4>

    <div class="card mb-3">
    <div class="card-body">
      <form action="{{ route('reports.monthly-sales') }}" method="GET" class="d-flex align-items-center">
      @include('reports.partials.month_year_filter')
      <button type="submit" class="btn btn-sm btn-outline-primary me-2">Tampilkan</button>
      <a href="{{ route('reports.print-monthly-sales', ['month' => $filterMonth ?? now()->month, 'year' => $filterYear ?? now()->year]) }}"
        target="_blank" class="btn btn-sm btn-success">Cetak</a>
      </form>
    </div>
    </div>

    <h5>Periode: {{ date('F', mktime(0, 0, 0, $filterMonth ?? now()->month, 1)) }} {{ $filterYear ?? now()->year }}</h5>

    <div class="row mt-3">
    <div class="col-md-6">
      <div class="card mb-3">
      <div class="card-header">Material (Umum & Cat)</div>
      <div class="card-body">
        <h6 class="card-title">Total Penjualan Material</h6>
        <p class="card-text">Rp {{ number_format($monthlySalesMaterial ?? 0, 0, ',', '.') }}</p>
      </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="card mb-3">
      <div class="card-header">Keramik</div>
      <div class="card-body">
        <h6 class="card-title">Total Penjualan Keramik</h6>
        <p class="card-text">Rp {{ number_format($monthlySalesKeramik ?? 0, 0, ',', '.') }}</p>
      </div>
      </div>
    </div>
    </div>

    <div class="card bg-light mb-3">
    <div class="card-body">
      <h5 class="card-title">Total Penjualan Keseluruhan</h5>
      <p class="card-text fw-bold">Rp {{ number_format($totalMonthlySales ?? 0, 0, ',', '.') }}</p>
    </div>
    </div>

    @if ($salesThisMonth && $salesThisMonth->count() > 0)
    <div class="card">
    <div class="card-header">
      Detail Transaksi Penjualan ({{ date('F', mktime(0, 0, 0, $filterMonth ?? now()->month, 1)) }}
      {{ $filterYear ?? now()->year }})
    </div>
    <div class="card-body">
      <table class="table table-sm table-striped">
      <thead>
      <tr>
      <th>No</th>
      <th>Invoice</th>
      <th>Tanggal</th>
      <th>Nama Barang</th>
      <th>Kategori</th>
      <th>Total</th>
      <th>Detail</th>
      </tr>
      </thead>
      <tbody>
      @foreach ($salesThisMonth as $key => $sale)
      @foreach ($sale->items as $saleItem)
      <tr>
      <td>{{ $loop->parent->iteration }}</td>
      <td>{{ $sale->invoice_number }}</td>
      <td>{{ $sale->sale_date->format('d-m-Y H:i') }}</td>
      <td>{{ $saleItem->item_name }}</td>
      <td>
      @if ($saleItem->item->category)
      @if (str_contains(strtoupper($saleItem->item->category->name), 'CAT') || strtoupper($saleItem->item->category->name) === 'UMUM')
      Material
      @else
      {{ $saleItem->item->category->name }}
      @endif
      @else
      -
      @endif
      </td>
      <td>Rp {{ number_format($saleItem->quantity * $saleItem->unit_price, 0, ',', '.') }}</td>
      <td>
      <a href="{{ route('penjualan.transaksi.show', $sale) }}" class="btn btn-sm btn-info">Detail</a>
      </td>
      </tr>
      @endforeach
      @endforeach
      </tbody>
      <tfoot>
      <tr class="fw-bold">
      <td colspan="5" class="text-end">Total Seluruh Transaksi:</td>
      <td>Rp {{ number_format($salesThisMonth->sum('grand_total'), 0, ',', '.') }}</td>
      <td></td>
      </tr>
      </tfoot>
      </table>
    </div>
    </div>
    @else
    <div class="alert alert-info">Tidak ada transaksi penjualan pada bulan
    {{ date('F', mktime(0, 0, 0, $filterMonth ?? now()->month, 1)) }} {{ $filterYear ?? now()->year }}.
    </div>
    @endif
  </div>
@endsection