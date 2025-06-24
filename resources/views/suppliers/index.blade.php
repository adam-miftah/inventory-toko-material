@extends('layouts.app')

@section('title', 'Daftar Supplier')

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
        <i class="fas fa-truck-loading me-2"></i>Daftar Supplier
      </h4>
      <a href="{{ route('suppliers.create') }}" class="btn btn-primary btn-sm">
        <i class="fas fa-plus-circle me-1"></i> Tambah Supplier
      </a>
      </div>
      <hr class="my-3">
      <div class="row align-items-center g-3">
      <div class="col-md-12">
        <div class="input-group input-group-sm" style="max-width: 350px; margin-left: auto;">
        <span class="input-group-text bg-light border-end-0">
          <i class="fas fa-search text-muted"></i>
        </span>
        <input type="text" id="custom-search-input" class="form-control border-start-0"
          placeholder="Cari nama, kontak, telepon...">
        </div>
      </div>
      </div>
    </div>

    <div class="card-body p-0">
      <div class="table-responsive">
      <table class="table table-hover align-middle mb-0 @if($suppliers->isEmpty()) is-empty @endif"
        id="suppliers-table" style="width:100%">
        <thead class="table-light">
        <tr>
          <th class="text-center" width="5%">No</th>
          <th>Nama Supplier</th>
          <th>Kontak Person</th>
          <th>Telepon</th>
          <th class="text-center">Jml. Pembelian</th>
          <th class="text-center" width="15%">Aksi</th>
        </tr>
        </thead>
        <tbody>
        @forelse ($suppliers as $supplier)
      <tr>
        <td data-label="No." class="text-center"></td>
        <td data-label="Supplier">
        <span class="fw-semibold">{{ $supplier->name }}</span>
        </td>
        <td data-label="Kontak">{{ $supplier->contact_person ?? '-' }}</td>
        <td data-label="Telepon">{{ $supplier->phone ?? '-' }}</td>
        <td data-label="Jml. Pembelian" class="text-center">
        <span class="badge bg-primary-subtle text-primary-emphasis">{{ $supplier->purchases_count }}</span>
        </td>
        <td data-label="Aksi" class="text-center">
        <div class="d-flex justify-content-center gap-2">
        <a href="{{ route('suppliers.show', $supplier) }}" class="btn btn-sm btn-outline-info"
        data-bs-toggle="tooltip" title="Detail"><i class="fas fa-eye"></i></a>
        <a href="{{ route('suppliers.edit', $supplier) }}" class="btn btn-sm btn-outline-warning"
        data-bs-toggle="tooltip" title="Edit"><i class="fas fa-edit"></i></a>
        <button type="button" class="btn btn-sm btn-outline-danger delete-btn"
        data-url="{{ route('suppliers.destroy', $supplier) }}" data-name="{{ $supplier->name }}"
        data-bs-toggle="tooltip" title="Hapus">
        <i class="fas fa-trash-alt"></i>
        </button>
        </div>
        </td>
      </tr>
      @empty
      <tr>
        <td colspan="6" class="text-center py-5 text-muted">
        <i class="fas fa-folder-open fa-3x mb-3"></i>
        <p class="mb-0">Belum ada data supplier.</p>
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
    font-size: .8rem;
    text-transform: uppercase;
    letter-spacing: .5px;
    }

    .table td {
    vertical-align: middle;
    font-size: 0.875rem;
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
      padding: .75rem 1rem;
    }

    .table td::before {
      content: attr(data-label);
      font-weight: 600;
      color: #6c757d;
      margin-right: 1rem;
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
    var tableElement = $('#suppliers-table:not(.is-empty)');

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
      order: [[1, 'asc']], // Urutkan berdasarkan nama supplier
      language: {
        search: "",
        zeroRecords: "Supplier tidak ditemukan.",
        info: "Menampilkan _START_ - _END_ dari _TOTAL_ supplier",
        infoEmpty: "Menampilkan 0 supplier",
        paginate: { next: "›", previous: "‹" }
      },
      columnDefs: [
        { searchable: false, orderable: false, targets: 0, render: (data, type, row, meta) => meta.row + meta.settings._iDisplayStart + 1 },
        { orderable: false, searchable: false, targets: 5 } // Aksi tidak bisa diurutkan/dicari
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

    // Logika hapus dengan AJAX dan SweetAlert
    $(document).on('click', '.delete-btn', function (e) {
      e.preventDefault();
      const button = $(this);
      const url = button.data('url');
      const name = button.data('name');
      const csrfToken = $('meta[name="csrf-token"]').attr('content');

      Swal.fire({
      title: 'Anda Yakin?',
      html: `Menghapus supplier <b>${name}</b> dapat mempengaruhi data pembelian terkait.`,
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
          Swal.fire('Berhasil!', response.success, 'success');
        },
        error: function (xhr) {
          let msg = 'Terjadi kesalahan. Silakan coba lagi.';
          if (xhr.responseJSON && xhr.responseJSON.error) {
          msg = xhr.responseJSON.error;
          }
          Swal.fire('Gagal!', msg, 'error');
        }
        });
      }
      });
    });
    });
  </script>
@endpush