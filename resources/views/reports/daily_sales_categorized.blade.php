@extends('layouts.app')

@section('title', 'Laporan Penjualan Harian per Kategori')

@section('content')
  <div class="container-fluid">
    <h4 class="mb-4">Laporan Penjualan Harian per Kategori</h4>

    <div class="card mb-3">
    <div class="card-body">
      <form action="{{ route('reports.daily-sales') }}" method="GET" class="d-flex align-items-center">
      <div class="me-2">
        <label for="date" class="form-label mb-0">Pilih Tanggal:</label>
      </div>
      <div class="me-2">
        <input type="date" class="form-control form-control-sm" id="date" name="date"
        value="{{ $filterDate ?? today()->toDateString() }}">
      </div>
      <button type="submit" class="btn btn-sm btn-outline-primary me-2">Tampilkan</button>
      <a href="{{ route('reports.print-daily-sales', ['date' => $filterDate ?? today()->toDateString()]) }}"
        target="_blank" class="btn btn-sm btn-success">Cetak</a>
      </form>
    </div>
    </div>

    <h5>Tanggal: {{ $filterDate ?? today()->toDateString() }}</h5>

    <div class="row mt-3">
    <div class="col-md-6">
      <div class="card mb-3">
      <div class="card-header">Material</div>
      <div class="card-body">
        <h6 class="card-title">Total Penjualan Material</h6>
        <p class="card-text">Rp {{ number_format($dailySalesMaterial ?? 0, 0, ',', '.') }}</p>
      </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="card mb-3">
      <div class="card-header">Keramik</div>
      <div class="card-body">
        <h6 class="card-title">Total Penjualan Keramik</h6>
        <p class="card-text">Rp {{ number_format($dailySalesKeramik ?? 0, 0, ',', '.') }}</p>
      </div>
      </div>
    </div>
    </div>

    <div class="card bg-light mb-3">
    <div class="card-body">
      <h5 class="card-title">Total Penjualan Keseluruhan</h5>
      <p class="card-text fw-bold">Rp {{ number_format($totalDailySales ?? 0, 0, ',', '.') }}</p>
    </div>
    </div>

    @if ($salesToday && $salesToday->count() > 0)
    <div class="card">
    <div class="card-header">
      Detail Transaksi Penjualan ({{ $filterDate ?? today()->toDateString() }})
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
      @foreach ($salesToday as $key => $sale)
      @foreach ($sale->items as $saleItem)
      <tr>
      <td>{{ $key + 1 }}</td>
      <td>{{ $sale->invoice_number }}</td>
      <td>{{ $sale->sale_date->format('d-m-Y H:i') }}</td>
      <td>{{ $saleItem->item_name }}</td>
      <td>{{ $saleItem->item->category->name ?? '-' }}</td>
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
      <td>Rp {{ number_format($salesToday->sum('grand_total'), 0, ',', '.') }}</td>
      <td></td>
      </tr>
      </tfoot>
      </table>
    </div>
    </div>
    @else
    <div class="alert alert-info">Tidak ada transaksi penjualan pada tanggal {{ $filterDate ?? today()->toDateString() }}.
    </div>
    @endif
  </div>
@endsection