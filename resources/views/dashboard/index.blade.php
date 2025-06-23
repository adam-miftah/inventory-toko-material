@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
  <div class="container-fluid">
    {{-- Header Sambutan --}}
    <div class="mb-4">
    <h3 class="fw-bold">Selamat Datang Kembali, {{ Auth::user()->name }}! 👋</h3>
    <p class="text-muted">Berikut adalah ringkasan aktivitas toko Anda hari ini,
      {{ \Carbon\Carbon::now()->isoFormat('dddd, DD MMMM YYYY') }}.</p>
    </div>

    {{-- Kartu Statistik Utama (KPI Cards) --}}
    <div class="row g-3 mb-4">
    {{-- Penjualan Bersih Hari Ini --}}
    <div class="col-xl-3 col-md-6">
      <div class="card stat-card h-100">
      <div class="card-body d-flex flex-column">
        <div>
        <h6 class="text-muted fw-normal mb-2">Penjualan Bersih Hari Ini</h6>
        </div>
        <div class="mt-auto d-flex justify-content-between align-items-end">
        <div>
          <h4 class="fw-bolder mb-0">Rp {{ number_format($netTodaySales ?? 0, 0, ',', '.') }}</h4>
          <small class="text-success fw-semibold">{{ $todayTransactionsCount ?? '0' }} Transaksi</small>
        </div>
        <i class="fas fa-wallet fa-2x text-success text-opacity-25"></i>
        </div>
      </div>
      </div>
    </div>
    {{-- Total Retur Hari Ini --}}
    <div class="col-xl-3 col-md-6">
      <div class="card stat-card h-100">
      <div class="card-body d-flex flex-column">
        <div>
        <h6 class="text-muted fw-normal mb-2">Total Retur Hari Ini</h6>
        </div>
        <div class="mt-auto d-flex justify-content-between align-items-end">
        <div>
          <h4 class="fw-bolder mb-0">Rp {{ number_format($todayReturns ?? 0, 0, ',', '.') }}</h4>
          <small class="text-danger fw-semibold">Dari Penjualan</small>
        </div>
        <i class="fas fa-undo-alt fa-2x text-danger text-opacity-25"></i>
        </div>
      </div>
      </div>
    </div>
    {{-- Pembelian Bulan Ini --}}
    <div class="col-xl-3 col-md-6">
      <div class="card stat-card h-100">
      <div class="card-body d-flex flex-column">
        <div>
        <h6 class="text-muted fw-normal mb-2">Pembelian Bulan Ini</h6>
        </div>
        <div class="mt-auto d-flex justify-content-between align-items-end">
        <div>
          <h4 class="fw-bolder mb-0">Rp {{ number_format($thisMonthPurchases ?? 0, 0, ',', '.') }}</h4>
          <small class="text-secondary fw-semibold">{{ $thisMonthPurchasesCount ?? '0' }} Pembelian</small>
        </div>
        <i class="fas fa-truck-loading fa-2x text-primary text-opacity-25"></i>
        </div>
      </div>
      </div>
    </div>
    {{-- Total Produk --}}
    <div class="col-xl-3 col-md-6">
      <div class="card stat-card h-100">
      <div class="card-body d-flex flex-column">
        <div>
        <h6 class="text-muted fw-normal mb-2">Total Produk</h6>
        </div>
        <div class="mt-auto d-flex justify-content-between align-items-end">
        <div>
          <h4 class="fw-bolder mb-0">{{ $totalItems ?? '0' }}</h4>
          <small class="text-info fw-semibold">{{ $totalCategories ?? '0' }} Kategori</small>
        </div>
        <i class="fas fa-boxes fa-2x text-info text-opacity-25"></i>
        </div>
      </div>
      </div>
    </div>
    </div>

    {{-- Grafik dan Chart --}}
    <div class="row g-3 mb-4">
    <div class="col-lg-7">
      <div class="card h-100 shadow-sm border-0">
      <div class="card-header bg-white">
        <h6 class="mb-0 fw-bold"><i class="fas fa-chart-bar me-2 text-primary"></i>Grafik Penjualan Bersih 7 Hari
        Terakhir</h6>
      </div>
      <div class="card-body" style="height: 320px;">
        <canvas id="salesChart"></canvas>
      </div>
      </div>
    </div>
    <div class="col-lg-5">
      <div class="card h-100 shadow-sm border-0">
      <div class="card-header bg-white">
        <h6 class="mb-0 fw-bold"><i class="fas fa-chart-pie me-2 text-primary"></i>Distribusi Stok per Kategori</h6>
      </div>
      <div class="card-body d-flex align-items-center justify-content-center p-2">
        <div class="w-50">
        <canvas id="inventoryChart"></canvas>
        </div>
        <div class="w-50 ps-3">
        @if (!empty($categoryDistribution) && collect($categoryDistribution)->sum() > 0)
        @foreach ($categoryDistribution as $category => $percentage)
        @php
        $colorIndex = $loop->index % count($categoryColors ?? [1]);
        $bgColor = isset($categoryColors) ? $categoryColors[$colorIndex] : '#6c757d';
        @endphp
        <div class="d-flex align-items-center mb-2">
          <span class="legend-dot" style="background-color: {{ $bgColor }};"></span>
          <small>{{ $category }} ({{ round($percentage, 1) }}%)</small>
        </div>
        @endforeach
      @else
      <small class="text-muted text-center d-block">Data kategori belum tersedia.</small>
      @endif
        </div>
      </div>
      </div>
    </div>
    </div>

    {{-- Tabel Informasi --}}
    <div class="row g-3">
    <div class="col-lg-5">
      <div class="card h-100 shadow-sm border-0">
      <div class="card-header bg-white">
        <h6 class="mb-0 fw-bold"><i class="fas fa-exclamation-triangle me-2 text-warning"></i>Stok Hampir Habis (<=
          10)</h6>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
        <table class="table table-hover mb-0">
          <tbody>
          @forelse ($lowStockItems as $item)
        <tr>
        <td>
        <div class="d-flex align-items-center">
          <div class="avatar-sm-table bg-danger-subtle text-danger-emphasis">{{ substr($item->name, 0, 1) }}
          </div>
          <div>
          <div class="fw-semibold">{{ Str::limit($item->name, 25) }}</div>
          <small class="text-muted">{{ $item->category->name ?? '-' }}</small>
          </div>
        </div>
        </td>
        <td class="text-end">
        <span class="badge bg-warning-subtle text-warning-emphasis p-2">{{ $item->stock }}</span>
        </td>
        </tr>
      @empty
        <tr>
        <td class="text-center text-muted p-4">Semua stok dalam kondisi aman.</td>
        </tr>
      @endforelse
          </tbody>
        </table>
        </div>
      </div>
      </div>
    </div>
    <div class="col-lg-7">
      <div class="card h-100 shadow-sm border-0">
      <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h6 class="mb-0 fw-bold"><i class="fas fa-receipt me-2 text-primary"></i>Transaksi Terakhir</h6>
        <a href="{{ route('penjualan.transaksi.index') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
        <table class="table table-hover mb-0">
          <tbody>
          @forelse ($recentTransactions as $transaction)
        <tr>
        <td>
        <div class="d-flex align-items-center">
          <div class="avatar-sm-table bg-primary-subtle text-primary-emphasis"><i
          class="fas fa-receipt"></i></div>
          <div>
          <div class="fw-semibold">{{ $transaction->invoice_number }}</div>
          <small class="text-muted">{{ $transaction->sale_date->diffForHumans() }}</small>
          </div>
        </div>
        </td>
        <td class="text-end">
        <div class="fw-bold">Rp {{ number_format($transaction->grand_total, 0, ',', '.') }}</div>
        <small class="text-muted">{{ $transaction->items->sum('quantity') }} item</small>
        </td>
        <td class="text-end">
        <a href="{{ route('penjualan.transaksi.show', $transaction->id) }}"
          class="btn btn-sm btn-light">Detail</a>
        </td>
        </tr>
      @empty
        <tr>
        <td class="text-center text-muted p-4">Belum ada transaksi terbaru.</td>
        </tr>
      @endforelse
          </tbody>
        </table>
        </div>
      </div>
      </div>
    </div>
    </div>
  </div>
@endsection

@push('styles')
  <style>
    .stat-card {
    transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
    }

    .stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .15) !important;
    }

    .avatar-sm-table {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    margin-right: 1rem;
    }

    .legend-dot {
    display: inline-block;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    margin-right: 8px;
    flex-shrink: 0;
    }

    .text-gradient {
    background: linear-gradient(135deg, var(--bs-primary), var(--bs-secondary));
    -webkit-background-clip: text;
    background-clip: text;
    color: transparent;
    }
  </style>
@endpush

@push('scripts')
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function () {
    const salesData = {!! json_encode($salesData ?? []) !!};
    const salesLabels = {!! json_encode($salesLabels ?? []) !!};
    const categoryDistributionLabels = {!! json_encode(isset($categoryDistribution) ? array_keys($categoryDistribution->toArray()) : []) !!};
    const categoryDistributionData = {!! json_encode(isset($categoryDistribution) ? array_values($categoryDistribution->toArray()) : []) !!};
    const categoryColors = {!! json_encode($categoryColors ?? []) !!};

    Chart.defaults.font.family = "'Poppins', sans-serif";
    Chart.defaults.plugins.legend.display = false;

    const salesCtx = document.getElementById('salesChart');
    if (salesCtx) {
      new Chart(salesCtx, {
      type: 'bar',
      data: {
        labels: salesLabels,
        datasets: [{
        label: 'Penjualan Bersih',
        data: salesData,
        backgroundColor: 'rgba(52, 152, 219, 0.7)',
        borderColor: 'rgba(52, 152, 219, 1)',
        borderWidth: 2,
        borderRadius: 4,
        barThickness: 20,
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { tooltip: { callbacks: { label: (context) => 'Rp ' + Number(context.raw).toLocaleString('id-ID') } } },
        scales: {
        y: {
          beginAtZero: true,
          ticks: {
          callback: (value) => {
            if (value >= 1000000) return 'Rp ' + (value / 1000000) + ' Jt';
            if (value >= 1000) return 'Rp ' + (value / 1000) + ' Rb';
            return 'Rp ' + value;
          }
          }
        },
        x: { grid: { display: false } }
        }
      }
      });
    }

    const inventoryCtx = document.getElementById('inventoryChart');
    if (inventoryCtx && categoryDistributionData.length > 0) {
      new Chart(inventoryCtx, {
      type: 'doughnut',
      data: {
        labels: categoryDistributionLabels,
        datasets: [{
        data: categoryDistributionData,
        backgroundColor: categoryColors,
        borderColor: '#fff',
        borderWidth: 2
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        cutout: '70%',
        plugins: { tooltip: { callbacks: { label: (context) => context.label + ': ' + Number(context.raw).toFixed(1) + '%' } } }
      }
      });
    }
    });
  </script>
@endpush