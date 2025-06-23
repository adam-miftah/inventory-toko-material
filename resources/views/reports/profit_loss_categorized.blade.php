@extends('layouts.app')

@section('title', 'Laporan Laba Rugi')

@section('content')
  <div class="container-fluid">
    {{-- Header Halaman dengan Filter --}}
    <div class="d-flex justify-content-between align-items-center flex-wrap mb-4">
    <h4 class="mb-0 fw-bold text-gradient">
      <i class="fas fa-balance-scale me-2"></i>Laporan Laba Rugi
    </h4>
    <form action="{{ route('reports.profit-loss') }}" method="GET" class="d-flex align-items-center gap-2">
      @include('reports.partials.month_year_filter')
      <button type="submit" class="btn btn-sm btn-primary">
      <i class="fas fa-search me-1"></i> Tampilkan
      </button>
      <a href="{{ route('reports.print-profit-loss', ['month' => $filterMonth ?? now()->month, 'year' => $filterYear ?? now()->year]) }}"
      target="_blank" class="btn btn-sm btn-success">
      <i class="fas fa-print me-1"></i> Cetak
      </a>
    </form>
    </div>

    {{-- Kartu Statistik Utama (KPI Cards) --}}
    <div class="row g-4">
    {{-- KARTU LABA RUGI MATERIAL --}}
    <div class="col-lg-4 col-md-6">
      <div class="card h-100 shadow-sm">
      <div class="card-header bg-white text-center">
        <h6 class="mb-0 fw-bold"><i class="fas fa-cogs me-2 text-primary"></i>Material & Umum</h6>
      </div>
      <div class="card-body p-4">
        <div class="d-flex justify-content-between align-items-center mb-2">
        <span class="text-muted">Penjualan Kotor</span>
        <span class="fw-semibold">Rp {{ number_format($penjualanMaterial ?? 0, 0, ',', '.') }}</span>
        </div>
        <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
        <span class="text-muted">Retur Penjualan</span>
        <span class="fw-semibold text-warning">- Rp {{ number_format($returMaterial ?? 0, 0, ',', '.') }}</span>
        </div>
        <div class="d-flex justify-content-between align-items-center">
        <span class="text-muted">Total Pembelian</span>
        <span class="fw-semibold text-danger">- Rp {{ number_format($pembelianMaterial ?? 0, 0, ',', '.') }}</span>
        </div>
      </div>
      <div
        class="card-footer text-center {{ ($labaRugiMaterial ?? 0) >= 0 ? 'bg-success-subtle text-success-emphasis' : 'bg-danger-subtle text-danger-emphasis' }}">
        <small class="fw-semibold">LABA / RUGI</small>
        <h4 class="fw-bold mb-0">Rp {{ number_format($labaRugiMaterial ?? 0, 0, ',', '.') }}</h4>
      </div>
      </div>
    </div>

    {{-- KARTU LABA RUGI KERAMIK --}}
    <div class="col-lg-4 col-md-6">
      <div class="card h-100 shadow-sm">
      <div class="card-header bg-white text-center">
        <h6 class="mb-0 fw-bold"><i class="fas fa-border-style me-2 text-warning"></i>Keramik</h6>
      </div>
      <div class="card-body p-4">
        <div class="d-flex justify-content-between align-items-center mb-2">
        <span class="text-muted">Penjualan Kotor</span>
        <span class="fw-semibold">Rp {{ number_format($penjualanKeramik ?? 0, 0, ',', '.') }}</span>
        </div>
        <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
        <span class="text-muted">Retur Penjualan</span>
        <span class="fw-semibold text-warning">- Rp {{ number_format($returKeramik ?? 0, 0, ',', '.') }}</span>
        </div>
        <div class="d-flex justify-content-between align-items-center">
        <span class="text-muted">Total Pembelian</span>
        <span class="fw-semibold text-danger">- Rp {{ number_format($pembelianKeramik ?? 0, 0, ',', '.') }}</span>
        </div>
      </div>
      <div
        class="card-footer text-center {{ ($labaRugiKeramik ?? 0) >= 0 ? 'bg-success-subtle text-success-emphasis' : 'bg-danger-subtle text-danger-emphasis' }}">
        <small class="fw-semibold">LABA / RUGI</small>
        <h4 class="fw-bold mb-0">Rp {{ number_format($labaRugiKeramik ?? 0, 0, ',', '.') }}</h4>
      </div>
      </div>
    </div>

    {{-- KARTU TOTAL LABA RUGI --}}
    <div class="col-lg-4 col-md-12">
      <div class="card h-100 shadow-sm bg-light">
      <div class="card-header bg-white text-center">
        <h6 class="mb-0 fw-bold"><i class="fas fa-wallet me-2 text-success"></i>Total Keseluruhan</h6>
      </div>
      <div class="card-body p-4">
        <div class="d-flex justify-content-between align-items-center mb-2">
        <span class="text-muted">Total Penjualan Kotor</span>
        <span class="fw-semibold">Rp {{ number_format($totalPendapatan ?? 0, 0, ',', '.') }}</span>
        </div>
        <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
        <span class="text-muted">Total Retur Penjualan</span>
        <span class="fw-semibold text-warning">- Rp {{ number_format($totalRetur ?? 0, 0, ',', '.') }}</span>
        </div>
        <div class="d-flex justify-content-between align-items-center">
        <span class="text-muted">Total Pembelian</span>
        <span class="fw-semibold text-danger">- Rp
          {{ number_format($totalBiayaPembelian ?? 0, 0, ',', '.') }}</span>
        </div>
      </div>
      <div
        class="card-footer text-center {{ ($totalLabaRugi ?? 0) >= 0 ? 'bg-success text-white' : 'bg-danger text-white' }}">
        <small class="fw-semibold">TOTAL LABA / RUGI BERSIH</small>
        <h4 class="fw-bold mb-0">Rp {{ number_format($totalLabaRugi ?? 0, 0, ',', '.') }}</h4>
      </div>
      </div>
    </div>
    </div>

    <div class="mt-4 text-center">
    <p class="text-muted small mb-0">
      * Perhitungan ini adalah laba kotor sederhana berdasarkan (Total Penjualan - Total Retur) - Total Pembelian pada
      periode yang dipilih.
    </p>
    <p class="text-muted small">
      * Biaya operasional dan faktor lain tidak termasuk dalam perhitungan ini.
    </p>
    </div>
  </div>
@endsection

@push('styles')
  <style>
    .text-gradient {
    background: linear-gradient(135deg, var(--primary), var(--secondary));
    -webkit-background-clip: text;
    background-clip: text;
    color: transparent;
    }
  </style>
@endpush