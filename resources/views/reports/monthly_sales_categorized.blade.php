@extends('layouts.app')

@section('title', 'Laporan Penjualan Bulanan')

@section('content')
  <div class="container-fluid">
    {{-- Header Halaman dengan Filter --}}
    <div class="d-flex justify-content-between align-items-center flex-wrap mb-4">
    <h4 class="mb-0 fw-bold text-gradient">
      <i class="fas fa-chart-pie me-2"></i>Laporan Penjualan Bulanan
    </h4>
    <form action="{{ route('reports.monthly-sales') }}" method="GET" class="d-flex align-items-center gap-2">
      @include('reports.partials.month_year_filter')
      <button type="submit" class="btn btn-sm btn-primary">
      <i class="fas fa-search me-1"></i> Tampilkan
      </button>
      <a href="{{ route('reports.print-monthly-sales', ['month' => $filterMonth ?? now()->month, 'year' => $filterYear ?? now()->year]) }}"
      target="_blank" class="btn btn-sm btn-success">
      <i class="fas fa-print me-1"></i> Cetak
      </a>
    </form>
    </div>

    {{-- Kartu Statistik Utama (KPI Cards) --}}
    <div class="row g-3 mb-4">
    {{-- Penjualan Bersih Material --}}
    <div class="col-xl-3 col-md-6">
      <div class="card stat-card h-100 border-start border-primary border-4">
      <div class="card-body d-flex flex-column">
        <div>
        <h6 class="text-muted mb-0">Penjualan Bersih</h6>
        <p class="mb-2 fw-semibold">Material & Umum</p>
        </div>
        <div class="mt-auto d-flex align-items-end justify-content-between">
        <h6 class="fw-bolder mb-0">Rp {{ number_format($netSalesMaterial ?? 0, 0, ',', '.') }}</h6>
        </div>
      </div>
      </div>
    </div>
    {{-- Penjualan Bersih Keramik --}}
    <div class="col-xl-3 col-md-6">
      <div class="card stat-card h-100 border-start border-warning border-4">
      <div class="card-body d-flex flex-column">
        <div>
        <h6 class="text-muted mb-0">Penjualan Bersih</h6>
        <p class="mb-2 fw-semibold">Keramik</p>
        </div>
        <div class="mt-auto d-flex align-items-end justify-content-between">
        <h6 class="fw-bolder mb-0">Rp {{ number_format($netSalesKeramik ?? 0, 0, ',', '.') }}</h6>
        </div>
      </div>
      </div>
    </div>
    {{-- Total Retur --}}
    <div class="col-xl-3 col-md-6">
      <div class="card stat-card h-100 border-start border-danger border-4">
      <div class="card-body d-flex flex-column">
        <div>
        <h6 class="text-muted mb-0">Total Retur</h6>
        <p class="mb-2 fw-semibold">Bulan Ini</p>
        </div>
        <div class="mt-auto d-flex align-items-end justify-content-between">
        <h6 class="fw-bolder mb-0">Rp {{ number_format($totalMonthlyReturns ?? 0, 0, ',', '.') }}</h6>
        </div>
      </div>
      </div>
    </div>
    {{-- Total Penjualan Bersih Keseluruhan --}}
    <div class="col-xl-3 col-md-6">
      <div class="card stat-card h-100 border-start border-success border-4">
      <div class="card-body d-flex flex-column">
        <div>
        <h6 class="text-muted mb-0">Total Penjualan</h6>
        <p class="mb-2 fw-semibold">Bersih (Net)</p>
        </div>
        <div class="mt-auto d-flex align-items-end justify-content-between">
        <h6 class="fw-bolder mb-0">Rp {{ number_format($totalNetMonthlySales ?? 0, 0, ',', '.') }}</h6>
        </div>
      </div>
      </div>
    </div>
    </div>

    {{-- Detail Transaksi Bulanan --}}
    <div class="card shadow-sm border-0">
    <div class="card-header bg-white p-3">
      <div class="d-flex justify-content-between align-items-center flex-wrap">
      <h6 class="mb-0 fw-bold">
        <i class="fas fa-list-ul me-2"></i>Detail Transaksi Bulan
        {{ \Carbon\Carbon::create()->month($filterMonth ?? now()->month)->isoFormat('MMMM') }}
        {{ $filterYear ?? now()->year }}
      </h6>
      <div class="input-group input-group-sm" style="max-width: 250px;">
        <span class="input-group-text bg-light border-end-0">
        <i class="fas fa-search text-muted"></i>
        </span>
        <input type="text" id="custom-search-input" class="form-control border-start-0"
        placeholder="Cari invoice, pelanggan...">
      </div>
      </div>
    </div>
    <div class="card-body p-0">
      @if ($salesThisMonth && $salesThisMonth->count() > 0)
      <div class="table-responsive">
      <table class="table table-hover align-middle mb-0" id="monthly-sales-table">
      <thead class="table-light">
      <tr>
        <th class="text-center" width="5%">No</th>
        <th>Invoice</th>
        <th>Tanggal</th>
        <th>Pelanggan</th>
        <th>Kasir</th>
        <th class="text-center">Jml. Item</th>
        <th class="text-end">Total Transaksi</th>
        <th class="text-center" width="10%">Aksi</th>
      </tr>
      </thead>
      <tbody>
      @foreach ($salesThisMonth as $sale)
      <tr>
      <td data-label="No." class="text-center">{{ $loop->iteration }}</td>
      <td data-label="Invoice"><span class="fw-bold text-primary">{{ $sale->invoice_number }}</span></td>
      <td data-label="Tanggal">{{ $sale->sale_date->isoFormat('DD MMM, HH:mm') }}</td>
      <td data-label="Pelanggan">{{ $sale->customer_name ?? 'Umum' }}</td>
      <td data-label="Kasir">{{ $sale->user->name }}</td>
      <td data-label="Jml. Item" class="text-center">
      <span class="badge bg-secondary-subtle text-secondary-emphasis" data-bs-toggle="tooltip"
      title="{{ $sale->items->pluck('item_name')->join(', ') }}">
      {{ $sale->items->sum('quantity') }}
      </span>
      </td>
      <td data-label="Total" class="text-end fw-bold">Rp {{ number_format($sale->grand_total, 0, ',', '.') }}
      </td>
      <td data-label="Aksi" class="text-center">
      <a href="{{ route('penjualan.transaksi.show', $sale) }}" class="btn btn-sm btn-outline-primary btn-icon"
      data-bs-toggle="tooltip" title="Lihat Detail">
      <i class="fas fa-eye"></i>
      </a>
      </td>
      </tr>
      @endforeach
      </tbody>
      </table>
      </div>
    @else
      <div class="text-center py-5 text-muted">
      <i class="fas fa-folder-open fa-3x mb-3"></i>
      <p class="mb-0">Tidak ada transaksi penjualan pada periode ini.</p>
      </div>
    @endif
    </div>
    </div>
  </div>
@endsection

@push('styles')
  <style>
    .text-gradient {
    background: linear-gradient(135deg, var(--bs-primary), var(--bs-secondary));
    -webkit-background-clip: text;
    background-clip: text;
    color: transparent;
    }

    .stat-card {
    transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
    }

    .stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .15);
    }

    .table th {
    font-weight: 600;
    font-size: 0.8rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    white-space: nowrap;
    }

    .table td {
    padding: 0.9rem 1rem;
    vertical-align: middle;
    font-size: 0.8rem;
    }

    .btn-icon {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 0;
    }

    @media (max-width: 768px) {
    .table thead {
      display: none;
    }

    .table tr {
      display: block;
      margin-bottom: 1rem;
      border: 1px solid #dee2e6;
      border-radius: .5rem;
      box-shadow: 0 .125rem .25rem rgba(0, 0, 0, .075);
    }

    .table td {
      display: flex;
      justify-content: space-between;
      align-items: center;
      border-bottom: 1px solid #f0f0f0;
      padding: 0.75rem 1rem;
    }

    .table td::before {
      content: attr(data-label);
      font-weight: 600;
      color: #6c757d;
      margin-right: 1rem;
      flex-shrink: 0;
    }

    .table td:last-child {
      border-bottom: 0;
    }
    }
  </style>
@endpush

@push('scripts')
  <script>
    $(document).ready(function () {
    if ($('#monthly-sales-table tbody tr').length > 1 || ($('#monthly-sales-table tbody tr').length === 1 && $('#monthly-sales-table tbody tr td').length > 1)) {
      var table = $('#monthly-sales-table').DataTable({
      paging: true,
      lengthChange: false,
      searching: true,
      info: true,
      ordering: true,
      autoWidth: false,
      responsive: false,
      dom: 'rt<"d-flex justify-content-between align-items-center p-3"ip>',
      language: {
        search: "",
        searchPlaceholder: "Cari...",
        zeroRecords: "Data tidak ditemukan.",
        info: "Menampilkan _START_ - _END_ dari _TOTAL_ transaksi",
        infoEmpty: "Menampilkan 0 transaksi",
        paginate: { next: ">", previous: "<" }
      },
      columnDefs: [
        { orderable: false, targets: [0, 7] }
      ]
      });

      $('#custom-search-input').on('keyup', function () {
      table.search(this.value).draw();
      });
    }

    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
      return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    });
  </script>
@endpush