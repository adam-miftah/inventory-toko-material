@extends('layouts.app')

@section('title', 'Daftar Retur Pembelian')

@section('content')
  <div class="container-fluid">
    {{-- Notifikasi Session --}}
    @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
    @if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    {{-- Card Utama --}}
    <div class="card shadow-sm border-0">
    <div class="card-header bg-white p-3">
      <div class="d-flex justify-content-between align-items-center flex-wrap">
      <h4 class="mb-0 fw-bold text-gradient">
        <i class="fas fa-undo-alt me-2"></i>Daftar Retur Pembelian
      </h4>
      <a href="{{ route('retur-pembelian.create') }}" class="btn btn-primary btn-sm">
        <i class="fas fa-plus-circle me-1"></i> Tambah Retur
      </a>
      </div>
      <hr class="my-3">
      {{-- KONTROL KUSTOM UNTUK DATATABLES --}}
      <div class="row align-items-center g-3">
      {{-- PERUBAHAN: Menghapus div 'Tampilkan entri' --}}
      <div class="col-md-12">
        <div class="input-group input-group-sm" style="max-width: 350px; margin-left: auto;">
        <span class="input-group-text bg-light border-end-0">
          <i class="fas fa-search text-muted"></i>
        </span>
        <input type="text" id="custom-search-input" class="form-control border-start-0"
          placeholder="Cari no. retur, no. faktur...">
        </div>
      </div>
      </div>
    </div>

    <div class="card-body p-0">
      <div class="table-responsive">
      <table class="table table-hover align-middle mb-0 @if($returPembelians->isEmpty()) is-empty @endif"
        id="purchase-returns-table" style="width:100%">
        <thead class="table-light">
        <tr>
          <th class="text-center" width="5%">No</th>
          <th>Nomor Retur</th>
          <th>Barang Diretur</th>
          <th>Tanggal</th>
          <th>Nomor Pembelian</th>
          <th class="text-end">Total</th>
          <th class="text-center" width="10%">Aksi</th>
        </tr>
        </thead>
        <tbody>
        @forelse ($returPembelians as $retur)
        <tr>
        <td data-label="No." class="text-center"></td>
        <td data-label="Nomor Retur">
        <span>{{ $retur->return_number ?? 'N/A' }}</span>
        </td>
        <td data-label="Barang" data-bs-toggle="tooltip" data-bs-placement="top"
        title="{{ $retur->items->pluck('item_name')->join(', ') }}">
        @if($retur->items->isNotEmpty())
        <span>{{ $retur->items->first()->item_name }}</span>
        @if($retur->items->count() > 1)
        <span
        class="badge bg-secondary-subtle text-secondary-emphasis ms-1">+{{ $retur->items->count() - 1 }}</span>
        @endif
      @else
        -
      @endif
        </td>
        <td data-label="Tanggal">{{ $retur->retur_date->isoFormat('DD MMM YY') }}</td>
        <td data-label="No. Pembelian" class="fw-bold text-decoration-none">
        @if($retur->pembelian)
        <a
        href="{{ route('pembelian.show', $retur->pembelian->id) }}">{{ $retur->pembelian->purchase_number }}</a>
      @else
        N/A
      @endif
        </td>
        <td data-label="Total" class="text-end fw-bold">Rp
        {{ number_format($retur->total_returned_amount, 0, ',', '.') }}
        </td>
        <td data-label="Aksi" class="text-center">
        <div class="d-flex justify-content-center gap-2">
          <a href="{{ route('retur-pembelian.show', $retur) }}" class="btn btn-sm btn-outline-info"
          data-bs-toggle="tooltip" title="Detail">
          <i class="fas fa-eye"></i>
          </a>
          <button type="button" class="btn btn-sm btn-outline-danger delete-btn"
          data-form-id="delete-form-{{ $retur->id }}" data-bs-toggle="tooltip" title="Hapus">
          <i class="fas fa-trash-alt"></i>
          </button>
          <form id="delete-form-{{ $retur->id }}" action="{{ route('retur-pembelian.destroy', $retur) }}"
          method="POST" class="d-none">
          @csrf
          @method('DELETE')
          </form>
        </div>
        </td>
        </tr>
      @empty
      <tr>
        <td colspan="7" class="text-center py-5 text-muted">
        <i class="fas fa-folder-open fa-3x mb-3"></i>
        <p class="mb-0">Belum ada data retur pembelian.</p>
        </td>
      </tr>
      @endforelse
        </tbody>
      </table>
      </div>
    </div>
    </div>
  </div>
@endsection

@push('styles')
  <style>
    .text-gradient {
    background: linear-gradient(135deg, var(--primary), var(--secondary));
    -webkit-background-clip: text;
    background-clip: text;
    color: transparent
    }

    .card-header {
    border-bottom: 1px solid #dee2e6
    }

    .table th {
    font-weight: 600;
    font-size: .8rem;
    text-transform: uppercase;
    letter-spacing: .5px;
    white-space: nowrap
    }

    .table td {
    padding: .9rem 1rem;
    vertical-align: middle
    }

    .btn-icon {
    width: 32px;
    height: 32px;
    border-radius: 50%
    }

    #datatable-length-container .form-select {
    font-size: .875rem;
    width: auto
    }

    #datatable-search-container {
    max-width: 350px;
    margin-left: auto
    }

    @media (max-width:991.98px) {
    #datatable-search-container {
      max-width: none;
      margin-left: 0
    }

    .table thead {
      display: none
    }

    .table tr {
      display: block;
      margin-bottom: 1rem;
      border: 1px solid #dee2e6;
      border-radius: .5rem;
      box-shadow: 0 .125rem .25rem rgba(0, 0, 0, .075)
    }

    .table td {
      display: flex;
      justify-content: space-between;
      align-items: center;
      border-bottom: 1px solid #f0f0f0;
      padding: .75rem 1rem
    }

    .table td::before {
      content: attr(data-label);
      font-weight: 600;
      color: #6c757d;
      margin-right: 1rem;
      flex-shrink: 0
    }

    .table td:last-child {
      border-bottom: 0
    }
    }

    .table.is-empty tbody tr:hover {
    background-color: transparent;
    cursor: default;
    }
  </style>
@endpush

@push('scripts')
  <script>
    $(document).ready(function () {
    var tableElement = $('#purchase-returns-table:not(.is-empty)');

    if (tableElement.length) {
      var table = tableElement.DataTable({
      dom: 'rt<"d-flex justify-content-between align-items-center p-3"ip>',
      paging: true,
      searching: true,
      // PERUBAHAN: Matikan fitur lengthChange
      lengthChange: false,
      ordering: true,
      info: true,
      autoWidth: false,
      responsive: false,
      order: [[3, 'desc']],
      language: {
        zeroRecords: "Tidak ada data retur pembelian yang cocok.",
        info: "Menampilkan _START_ - _END_ dari _TOTAL_ retur",
        infoEmpty: "Menampilkan 0 retur",
        paginate: { next: "›", previous: "‹" }
      },
      columnDefs: [
        { searchable: false, orderable: false, targets: 0, render: function (data, type, row, meta) { return meta.row + meta.settings._iDisplayStart + 1; } },
        { orderable: false, targets: [2, 6] }
      ],
      drawCallback: function (settings) {
        const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
        [...tooltipTriggerList].map(tooltip => new bootstrap.Tooltip(tooltip));
      }
      });

      $('#custom-search-input').on('keyup', function () {
      table.search(this.value).draw();
      });
    }

    // Logika SweetAlert
    $(document).on('click', '.delete-btn', function (e) {
      e.preventDefault();
      var form = document.getElementById($(this).data('form-id'));
      Swal.fire({
      title: 'Anda Yakin?',
      text: "Data retur pembelian yang dihapus tidak dapat dikembalikan.",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      cancelButtonColor: '#6c757d',
      confirmButtonText: 'Ya, Hapus!',
      cancelButtonText: 'Batal'
      }).then((result) => {
      if (result.isConfirmed) {
        form.submit();
      }
      });
    });
    });
  </script>
@endpush