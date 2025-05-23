@extends('layouts.app')

@section('title', 'Laporan Laba Rugi per Kategori')

@section('content')
  <div class="container-fluid">
    <h4 class="mb-4">Laporan Laba Rugi per Kategori</h4>

    <div class="card mb-3">
    <div class="card-body">
      <form action="{{ route('reports.profit-loss') }}" method="GET" class="d-flex align-items-center">
      @include('reports.partials.month_year_filter')
      <button type="submit" class="btn btn-sm btn-outline-primary me-2">Tampilkan</button>
      <a href="{{ route('reports.print-profit-loss', ['month' => $filterMonth ?? now()->month, 'year' => $filterYear ?? now()->year]) }}"
        target="_blank" class="btn btn-sm btn-success">Cetak</a>
      </form>
    </div>
    </div>

    <h5>Periode: {{ date('F', mktime(0, 0, 0, $filterMonth ?? now()->month, 1)) }}
    {{ $filterYear ?? now()->year }}
    </h5>

    <div class="row mt-3">
    <div class="col-md-6">
      <div class="card mb-3">
      <div class="card-header">Material (Umum & Cat)</div>
      <div class="card-body">
        <p>Total Penjualan: Rp {{ number_format($penjualanMaterial ?? 0, 0, ',', '.') }}</p>
        <p>Total Pembelian: Rp {{ number_format($pembelianMaterial ?? 0, 0, ',', '.') }}</p>
        <h5 class="{{ ($labaRugiMaterial ?? 0) >= 0 ? 'text-success' : 'text-danger' }}">
        Laba Rugi: Rp {{ number_format($labaRugiMaterial ?? 0, 0, ',', '.') }}
        </h5>
      </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="card mb-3">
      <div class="card-header">Keramik</div>
      <div class="card-body">
        <p>Total Penjualan: Rp {{ number_format($penjualanKeramik ?? 0, 0, ',', '.') }}</p>
        <p>Total Pembelian: Rp {{ number_format($pembelianKeramik ?? 0, 0, ',', '.') }}</p>
        <h5 class="{{ ($labaRugiKeramik ?? 0) >= 0 ? 'text-success' : 'text-danger' }}">
        Laba Rugi: Rp {{ number_format($labaRugiKeramik ?? 0, 0, ',', '.') }}
        </h5>
      </div>
      </div>
    </div>
    </div>

    <div class="card bg-light">
    <div class="card-body">
      <h5>Total Laba Rugi Keseluruhan</h5>
      <h4 class="{{ ($totalLabaRugi ?? 0) >= 0 ? 'text-success' : 'text-danger' }}">
      Rp {{ number_format($totalLabaRugi ?? 0, 0, ',', '.') }}
      </h4>
      <p class="text-muted small">
      * Perhitungan ini adalah perkiraan sederhana berdasarkan total penjualan dan total pembelian per
      kategori.
      Biaya operasional, retur, dan faktor lain tidak termasuk dalam perhitungan ini.
      </p>
    </div>
    </div>
  </div>
@endsection