@extends('layouts.app')

@section('title', 'Daftar Retur Penjualan')

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
        <i class="fas fa-undo-alt me-2"></i>Daftar Retur Penjualan
      </h4>
      <a href="{{ route('penjualan.retur.create') }}" class="btn btn-primary btn-sm text-nowrap">
        <i class="fas fa-plus-circle me-1"></i> Buat Retur
      </a>
      </div>
      <hr class="my-3">
      {{-- Kontrol Pencarian untuk DataTables --}}
      <div class="row align-items-center">
      <div class="col-md-12">
        <div class="input-group input-group-sm" style="max-width: 350px; margin-left: auto;">
        <span class="input-group-text bg-light border-end-0">
          <i class="fas fa-search text-muted"></i>
        </span>
        <input type="text" id="custom-search-input" class="form-control border-start-0"
          placeholder="Cari nomor retur, faktur, pelanggan...">
        </div>
      </div>
      </div>
    </div>

    <div class="card-body p-0">
      <div class="table-responsive">
      <table class="table table-hover align-middle mb-0 @if($saleReturns->isEmpty()) is-empty @endif"
        id="sale-returns-table" style="width:100%">
        <thead class="table-light">
        <tr>
          <th class="text-center" width="5%">No</th>
          <th>Nomor Retur</th>
          <th>Tanggal</th>
          <th>Pelanggan</th>
          <th class="text-end">Total Retur</th>
          <th>Kasir</th>
          <th class="text-center" width="10%">Aksi</th>
        </tr>
        </thead>
        <tbody>
        @forelse ($saleReturns as $return)
      <tr>
        <td data-label="No." class="text-center fw-semibold"></td>
        <td data-label="Nomor Retur">
        <a href="{{ route('penjualan.retur.show', $return) }}"
        class="fw-bold text-primary text-decoration-none">{{ $return->return_number }}</a>
        <small class="d-block text-muted">Faktur: {{ $return->sale->invoice_number ?? 'N/A' }}</small>
        </td>
        <td data-label="Tanggal" data-order="{{ $return->return_date->timestamp }}">
        {{ $return->return_date->isoFormat('DD MMM YY, HH:mm') }}
        </td>
        <td data-label="Pelanggan">{{ $return->sale->customer_name ?? 'Umum' }}</td>
        <td data-label="Total Retur" class="text-end fw-semibold"
        data-order="{{ $return->total_returned_amount }}">
        Rp {{ number_format($return->total_returned_amount, 0, ',', '.') }}
        </td>
        <td data-label="Kasir">
        <div class="d-flex align-items-center">
        <div class="avatar-sm me-2" data-bs-toggle="tooltip" title="{{ $return->user->name }}">
        {{ substr($return->user->name, 0, 1) }}
        </div>
        <span class="d-none d-md-inline">{{ $return->user->name }}</span>
        </div>
        </td>
        <td data-label="Aksi" class="text-center">
        <div class="d-flex justify-content-center gap-2">
        <a href="{{ route('penjualan.retur.show', $return) }}" class="btn btn-sm btn-info rounded-circle"
        data-bs-toggle="tooltip" title="Detail">
        <i class="fas fa-eye"></i>
        </a>
        {{-- <button type="button" class="btn btn-sm btn-outline-danger rounded-circle delete-btn"
        data-url="{{ route('penjualan.retur.destroy', $return) }}" data-name="{{ $return->return_number }}"
        data-bs-toggle="tooltip" title="Hapus">
        <i class="fas fa-trash-alt"></i>
        </button> --}}
        </div>
        </td>
      </tr>
      @empty
      <tr>
        <td colspan="7" class="text-center py-5 text-muted">
        <i class="fas fa-box-open fa-3x mb-3"></i>
        <p class="mb-0">Belum ada data retur penjualan.</p>
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
    color: transparent;
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
    font-size: 0.875rem;
    }

    .avatar-sm {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: var(--bs-primary-bg-subtle);
    color: var(--bs-primary-text-emphasis);
    font-weight: 600;
    }

    @media (max-width: 991.98px) {
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
    var tableElement = $('#sale-returns-table:not(.is-empty)');

    if (tableElement.length) {
      var table = tableElement.DataTable({
      dom: 'rt<"d-flex justify-content-between align-items-center p-3"ip>',
      paging: true,
      searching: true,
      lengthChange: false,
      ordering: true,
      info: true,
      autoWidth: false,
      responsive: false,
      order: [[2, 'desc']],
      language: {
        search: "",
        zeroRecords: "Tidak ada data retur yang cocok.",
        info: "Menampilkan _START_ - _END_ dari _TOTAL_ retur",
        infoEmpty: "Menampilkan 0 retur",
        paginate: { next: "›", previous: "‹" }
      },
      columnDefs: [
        {
        searchable: false, orderable: false, targets: 0,
        render: (data, type, row, meta) => meta.row + meta.settings._iDisplayStart + 1
        },
        { orderable: false, searchable: false, targets: 6 }
      ],
      drawCallback: function (settings) {
        const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
        [...tooltipTriggerList].map(tooltip => new bootstrap.Tooltip(tooltip));
      }
      });

      $('#custom-search-input').on('keyup', function () {
      table.search(this.value).draw();
      });
    } else {
      const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
      [...tooltipTriggerList].map(tooltip => new bootstrap.Tooltip(tooltip));
    }

    // Logika SweetAlert untuk Hapus via AJAX
    $(document).on('click', '.delete-btn', function (e) {
      e.preventDefault();

      const button = $(this);
      const url = button.data('url');
      const returnName = button.data('name');
      const csrfToken = $('meta[name="csrf-token"]').attr('content');

      Swal.fire({
      title: 'Anda Yakin?',
      html: `Menghapus retur <b>#${returnName}</b> akan mengurangi stok barang. Aksi ini tidak dapat dibatalkan.`,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      cancelButtonColor: '#6c757d',
      confirmButtonText: 'Ya, Hapus!',
      cancelButtonText: 'Batal'
      }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
        url: url,
        type: 'POST',
        data: {
          _token: csrfToken,
          _method: 'DELETE'
        },
        success: function (response) {
          if (typeof table !== 'undefined') {
          table.row(button.closest('tr')).remove().draw(false);
          } else {
          location.reload();
          }

          Swal.fire({
          title: 'Berhasil Dihapus!',
          text: response.success,
          icon: 'success',
          timer: 2000,
          showConfirmButton: false
          });
        },
        error: function (xhr) {
          let errorMsg = 'Terjadi kesalahan saat menghapus data.';
          if (xhr.responseJSON && xhr.responseJSON.error) {
          errorMsg = xhr.responseJSON.error;
          }
          Swal.fire('Gagal!', errorMsg, 'error');
        }
        });
      }
      });
    });
    });
  </script>
@endpush