@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
  <div class="container-fluid">
    <div class="page-header mb-4">
    <div class="d-flex justify-content-between align-items-center">
      <h6 class="page-title">
      <i class="fas fa-tachometer-alt me-2"></i>Dashboard
      </h6>
      <nav aria-label="breadcrumb">
      <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item active" aria-current="page">Home</li>
      </ol>
      </nav>
    </div>
    </div>

    <div class="row mb-4 animate-fade" style="opacity: 0; transform: translateY(20px);">
    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-start-lg border-primary h-100 hover-scale">
      <div class="card-body">
        <div class="d-flex align-items-center">
        <div class="flex-grow-1">
          <h6 class="text-muted fw-normal mb-2">Total Jenis Barang</h6>
          <h3 class="mb-0">{{ $totalCategories ?? '0' }}</h3>
          <small class="text-success">
          <i class="fas fa-list me-1"></i> {{ $newCategoriesCount ?? '0' }} Baru
          </small>
        </div>
        <div class="icon-circle bg-primary-light">
          <i class="fas fa-tags text-primary"></i>
        </div>
        </div>
      </div>
      </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-start-lg border-success h-100 hover-scale">
      <div class="card-body">
        <div class="d-flex align-items-center">
        <div class="flex-grow-1">
          <h6 class="text-muted fw-normal mb-2">Total Produk</h6>
          <h3 class="mb-0">{{ $totalItems ?? '0' }}</h3>
          <small class="text-success">
          <i class="fas fa-boxes me-1"></i> {{ $newItemsCount ?? '0' }} Baru
          </small>
        </div>
        <div class="icon-circle bg-success-light">
          <i class="fas fa-box text-success"></i>
        </div>
        </div>
      </div>
      </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-start-lg border-warning h-100 hover-scale">
      <div class="card-body">
        <div class="d-flex align-items-center">
        <div class="flex-grow-1">
          <h6 class="text-muted fw-normal mb-2">Transaksi Hari Ini</h6>
          <h3 class="mb-0">Rp {{ number_format($todaySales ?? 0, 0, ',', '.') }}</h3>
          <small class="text-success">
          <i class="fas fa-cash-register me-1"></i> {{ $todayTransactionsCount ?? '0' }} Transaksi
          </small>
        </div>
        <div class="icon-circle bg-warning-light">
          <i class="fas fa-money-bill-wave text-warning"></i>
        </div>
        </div>
      </div>
      </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-start-lg border-info h-100 hover-scale">
      <div class="card-body">
        <div class="d-flex align-items-center">
        <div class="flex-grow-1">
          <h6 class="text-muted fw-normal mb-2">Pembelian Bulan Ini</h6>
          <h3 class="mb-0">Rp {{ number_format($thisMonthPurchases ?? 0, 0, ',', '.') }}</h3>
          <small class="text-info">
          <i class="fas fa-truck me-1"></i> {{ $thisMonthPurchasesCount ?? '0' }} Pembelian
          </small>
        </div>
        <div class="icon-circle bg-info-light">
          <i class="fas fa-truck-loading text-info"></i>
        </div>
        </div>
      </div>
      </div>
    </div>
    </div>

    <div class="row mb-4 animate-fade" style="opacity: 0; transform: translateY(20px);">
    <div class="col-lg-8 mb-4">
      <div class="card h-100">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h6 class="mb-0">
        <i class="fas fa-chart-line me-2"></i>Grafik Penjualan 7 Hari Terakhir
        </h6>
        <div class="dropdown">
        <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
          {{ now()->format('d M Y') }}
        </button>
        <ul class="dropdown-menu dropdown-menu-end">
          <li><a class="dropdown-item" href="#">7 Hari Terakhir</a></li>
          <li><a class="dropdown-item" href="#">Bulan Ini</a></li>
          <li><a class="dropdown-item" href="#">Tahun Ini</a></li>
        </ul>
        </div>
      </div>
      <div class="card-body">
        <canvas id="salesChart" height="250"></canvas>
      </div>
      </div>
    </div>

    <div class="col-lg-4 mb-4">
      <div class="card h-100">
      <div class="card-header">
        <h6 class="mb-0">
        <i class="fas fa-chart-pie me-2"></i>Distribusi Kategori Produk
        </h6>
      </div>
      <div class="card-body d-flex align-items-center justify-content-center">
        <canvas id="inventoryChart" height="250"></canvas>
        <div class="ms-3 d-flex flex-column justify-content-center">
        @if (!empty($categoryDistribution))
        @foreach ($categoryDistribution as $category => $percentage)
        <div class="d-flex align-items-center mb-2">
        <span class="badge rounded-pill"
        style="background-color: {{ $categoryColors[$loop->index % count($categoryColors)] }}; width: 15px; height: 15px; margin-right: 5px;"></span>
        <small>{{ $category }} ({{ round($percentage, 1) }}%)</small>
        </div>
        @endforeach
      @else
      <small class="text-muted text-center">Data kategori belum tersedia.</small>
      @endif
        </div>
      </div>
      </div>
    </div>
    </div>

    <div class="row animate-fade" style="opacity: 0; transform: translateY(20px);">
    <div class="col-lg-6 mb-4">
      <div class="card h-100">
      <div class="card-header">
        <h6 class="mb-0">
        <i class="fas fa-list-alt me-2"></i>Aktivitas Terakhir
        </h6>
      </div>
      <div class="card-body p-0">
        <div class="list-group list-group-flush">
        @if (!empty($recentActivities))
        @foreach ($recentActivities as $activity)
        <a href="#" class="list-group-item list-group-item-action">
        <div class="d-flex align-items-center">
        <div
        class="avatar avatar-sm me-3 bg-{{ $activity['icon_color'] }}-light text-{{ $activity['icon_color'] }} rounded-circle">
        <i class="{{ $activity['icon'] }}"></i>
        </div>
        <div class="flex-grow-1">
        <div class="d-flex justify-content-between">
        <h6 class="mb-1">{{ $activity['title'] }}</h6>
        <small class="text-muted">{{ $activity['time'] }}</small>
        </div>
        <p class="small mb-0 text-muted">{{ $activity['description'] }}</p>
        </div>
        </div>
        </a>
        @endforeach
      @else
      <div class="list-group-item text-center text-muted">Tidak ada aktivitas terbaru.</div>
      @endif
        </div>
      </div>
      <div class="card-footer text-center">
        <a href="#" class="small">Lihat Semua Aktivitas</a>
      </div>
      </div>
    </div>

    <div class="col-lg-6 mb-4">
      <div class="card h-100">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h6 class="mb-0">
        <i class="fas fa-exclamation-circle me-2"></i>Stok Hampir Habis
        </h6>
        <a href="{{ route('inventory.items') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
        <table class="table table-hover mb-0">
          <thead class="bg-light">
          <tr>
            <th>Barang</th>
            <th>Kategori</th>
            <th>Stok</th>
            <th>Action</th>
          </tr>
          </thead>
          <tbody>
          @if (!empty($lowStockItems))
          @foreach ($lowStockItems as $item)
        <tr>
        <td>
        <div class="d-flex align-items-center">
          <img src="{{ asset('images/products/default.png') }}" class="rounded-circle me-2" width="30"
          height="30">
          <span>{{ $item->name }}</span>
        </div>
        </td>
        <td>{{ $item->category->name ?? '-' }}</td>
        <td>
        <span class="badge bg-warning">{{ $item->stock }}</span>
        </td>
        <td>
        <a href="{{ route('pembelian.create') }}" class="btn btn-sm btn-outline-primary">
          <i class="fas fa-cart-plus"></i> Beli
        </a>
        </td>
        </tr>
        @endforeach
      @else
        <tr>
        <td colspan="4" class="text-center text-muted">Tidak ada barang dengan stok hampir habis.</td>
        </tr>
      @endif
          </tbody>
        </table>
        </div>
      </div>
      </div>
    </div>
    </div>

    <div class="row animate-fade" style="opacity: 0; transform: translateY(20px);">
    <div class="col-12 mb-4">
      <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h6 class="mb-0">
        <i class="fas fa-receipt me-2"></i>Transaksi Terakhir
        </h6>
        <a href="{{ route('penjualan.transaksi.index') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
        <table class="table table-hover mb-0">
          <thead class="bg-light">
          <tr>
            <th>No. Transaksi</th>
            <th>Tanggal</th>
            <th>Total</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
          </thead>
          <tbody>
          @if (!empty($recentTransactions))
          @foreach ($recentTransactions as $transaction)
        <tr>
        <td>{{ $transaction->transaction_code }}</td>
        <td>{{ $transaction->transaction_date->format('d M Y') }}</td>
        <td>Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</td>
        <td><span class="badge bg-success">Selesai</span></td>
        <td>
        <a href="{{ route('penjualan.transaksi.show', $transaction->id) }}"
          class="btn btn-sm btn-outline-primary">
          <i class="fas fa-eye"></i>
        </a>
        </td>
        </tr>
        @endforeach
      @else
        <tr>
        <td colspan="5" class="text-center text-muted">Belum ada transaksi.</td>
        </tr>
      @endif
          </tbody>
        </table>
        </div>
      </div>
      </div>
    </div>
    </div>
  </div>
@endsection

@push('scripts')
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function () {
    // Sales Chart
    const salesCtx = document.getElementById('salesChart').getContext('2d');
    new Chart(salesCtx, {
      type: 'bar',
      data: {
      labels: ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'],
      datasets: [{
        label: 'Total Penjualan',
        data: [7500000, 8200000, 6800000, 9200000, 8800000, 11500000, 9500000],
        backgroundColor: 'rgba(52, 152, 219, 0.7)',
        borderColor: 'rgba(52, 152, 219, 1)',
        borderWidth: 1
      }]
      },
      options: {
      responsive: true,
      plugins: {
        legend: {
        display: false
        },
        tooltip: {
        callbacks: {
          label: function (context) {
          return 'Rp ' + context.raw.toLocaleString('id-ID');
          }
        }
        }
      },
      scales: {
        y: {
        beginAtZero: true,
        ticks: {
          callback: function (value) {
          return 'Rp ' + value.toLocaleString('id-ID');
          }
        }
        }
      }
      }
    });

    // Inventory Chart
    const inventoryCtx = document.getElementById('inventoryChart').getContext('2d');
    new Chart(inventoryCtx, {
      type: 'doughnut',
      data: {
      labels: @json(array_keys($categoryDistribution ?? [])),
      datasets: [{
        data: @json(array_values($categoryDistribution ?? [])),
        backgroundColor: @json($categoryColors ?? []),
        borderWidth: 1
      }]
      },
      options: {
      responsive: true,
      plugins: {
        legend: {
        display: false
        }
      }
      }
    });

    // Animate elements on load
    const animateElements = document.querySelectorAll('.animate-fade');
    animateElements.forEach(el => {
      el.style.opacity = '1';
      el.style.transform = 'translateY(0)';
    });
    });
  </script>
@endpush