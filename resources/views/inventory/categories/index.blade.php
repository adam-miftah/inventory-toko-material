@extends('layouts.app')

@section('title', 'Jenis Barang')

@section('content')
  <div class="container-fluid">
    <div class="card shadow-sm border-0">
    {{-- HEADER KARTU TERPADU --}}
    <div class="card-header bg-white p-3">
      <div class="d-flex justify-content-between align-items-center flex-wrap">
      <h4 class="mb-0 fw-bold text-gradient">
        <i class="fas fa-tags me-2"></i>Jenis Barang
      </h4>
      <a href="{{ route('inventory.categories.create') }}" class="btn btn-primary btn-sm">
        <i class="fas fa-plus-circle me-1"></i> Tambah Jenis Barang
      </a>
      </div>
      <hr class="my-3">
      {{-- KONTROL KUSTOM UNTUK DATATABLES --}}
      <div class="row align-items-center g-3">
      <div class="col-md-4">
        <div id="datatable-length-container"></div>
      </div>
      <div class="col-md-8">
        <div id="datatable-search-container"></div>
      </div>
      </div>
    </div>

    {{-- BODY KARTU --}}
    <div class="card-body p-0">
      @if (session('success'))
      <div class="alert alert-success alert-dismissible fade show m-3" role="alert">
      <i class="fas fa-check-circle me-2"></i>
      {{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    @endif
      @if (session('error')) {{-- Tambahkan notifikasi error --}}
      <div class="alert alert-danger alert-dismissible fade show m-3" role="alert">
      <i class="fas fa-times-circle me-2"></i>
      {{ session('error') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    @endif


      <div class="table-responsive">
      <table class="table table-hover align-middle mb-0 @if($categories->isEmpty()) is-empty @endif"
        id="categories-table" style="width:100%">
        <thead class="table-light">
        <tr>
          <th class="text-center" width="5%">No.</th>
          <th>Nama Jenis</th>
          <th>Tipe</th>
          <th class="text-center" width="15%">Aksi</th>
        </tr>
        </thead>
        <tbody>
        @forelse ($categories as $category)
        <tr>
        {{-- Kolom nomor ini akan diisi oleh JavaScript --}}
        <td class="text-center"></td>
        <td data-label="Nama Jenis">
        <div class="d-flex align-items-center">
          @php
        $icon = 'fa-box';
        if ($category->type === 'cat')
        $icon = 'fa-paint-roller';
        if ($category->type === 'keramik')
        $icon = 'fa-border-style';
        if ($category->type === 'luar')
        $icon = 'fa-shipping-fast';
      @endphp
          <div class="icon-circle me-3">
          <i class="fas {{ $icon }}"></i>
          </div>
          <span class="fw-semibold">{{ $category->name }}</span>
        </div>
        </td>
        <td data-label="Tipe">
        @php
        $badgeClass = 'secondary';
        if ($category->type === 'cat')
        $badgeClass = 'info';
        if ($category->type === 'keramik')
        $badgeClass = 'warning';
        if ($category->type === 'luar')
        $badgeClass = 'primary';
      @endphp
        <span
          class="badge bg-{{$badgeClass}}-subtle text-{{$badgeClass}}-emphasis border border-{{$badgeClass}}-subtle">
          {{ Str::title($category->type) }}
        </span>
        </td>
        <td data-label="Aksi" class="text-center">
        <div class="d-flex justify-content-center gap-2">
          <a href="{{ route('inventory.categories.edit', $category) }}" class="btn btn-sm btn-warning"
          data-bs-toggle="tooltip" title="Edit">
          <i class="fas fa-edit"></i>
          </a>
          <button type="button" class="btn btn-sm btn-danger delete-btn"
          data-form-id="delete-form-{{ $category->id }}" data-category-name="{{ $category->name }}"
          data-bs-toggle="tooltip" title="Hapus">
          <i class="fas fa-trash-alt"></i>
          </button>
          <form id="delete-form-{{ $category->id }}"
          action="{{ route('inventory.categories.destroy', $category) }}" method="POST" class="d-none">
          @csrf
          @method('DELETE')
          </form>
        </div>
        </td>
        </tr>
      @empty
      <tr>
        <td colspan="4" class="text-center py-5 text-muted">
        <i class="fas fa-box-open fa-3x mb-3"></i>
        <p class="mb-0">Tidak ada jenis barang yang ditemukan.</p>
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
  {{-- CSS Kustom untuk Tampilan Baru --}}
  <style>
    .text-gradient {
    background: linear-gradient(135deg, var(--primary), var(--secondary));
    -webkit-background-clip: text;
    background-clip: text;
    color: transparent;
    }

    .card-header {
    border-bottom: 1px solid #dee2e6;
    }

    .table th {
    font-weight: 600;
    font-size: 0.8rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    }

    .table td {
    padding: 0.9rem 1rem;
    vertical-align: middle;
    }

    .icon-circle {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: #f8f9fa;
    color: #6c757d;
    }

    #datatable-length-container .form-select,
    #datatable-search-container .form-control {
    font-size: 0.875rem;
    }

    #datatable-search-container {
    max-width: 350px;
    margin-left: auto;
    }

    @media (max-width: 768px) {
    #datatable-search-container {
      max-width: none;
      margin-left: 0;
    }

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
    var tableElement = $('#categories-table:not(.is-empty)');

    if (tableElement.length) {
      var table = tableElement.DataTable({
      dom: 'rt<"row align-items-center px-3 py-2"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7 d-flex justify-content-end"p>>',
      paging: true,
      lengthChange: true,
      searching: true,
      ordering: true,
      info: true,
      autoWidth: false,
      responsive: false,
      order: [[1, 'asc']],
      language: {
        search: "", searchPlaceholder: "Cari jenis barang...", lengthMenu: "Tampilkan _MENU_ entri",
        zeroRecords: "Tidak ada jenis barang yang cocok.", info: "Menampilkan _START_ - _END_ dari _TOTAL_ jenis",
        infoEmpty: "Menampilkan 0 jenis", paginate: { next: "›", previous: "‹" }
      },
      columnDefs: [
        { searchable: false, orderable: false, targets: 0, render: function (data, type, row, meta) { return meta.row + meta.settings._iDisplayStart + 1; } },
        { orderable: false, searchable: false, targets: 3 }
      ],
      drawCallback: function (settings) {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
        });
      }
      });

      $('#categories-table_length').appendTo('#datatable-length-container');
      $('#categories-table_filter').appendTo('#datatable-search-container');

      $('#datatable-length-container .form-select').addClass('form-select-sm').css('width', 'auto');
      $('#datatable-length-container label').addClass('form-label me-2 mb-0 small text-muted').contents().filter(function () { return this.nodeType === 3; }).remove();
      $('#datatable-search-container .form-control').addClass('form-control-sm');
      $('#datatable-search-container label').addClass('input-group input-group-sm').contents().filter(function () { return this.nodeType === 3; }).remove();
      $('#datatable-search-container input').addClass('border-start-0').prepend($('<span class="input-group-text bg-light border-end-0"><i class="fas fa-search text-muted"></i></span>'));
    }


    $(document).on('click', '.delete-btn', function (e) {
      e.preventDefault();
      const button = $(this);
      const categoryName = button.data('category-name');
      const form = document.getElementById(button.data('form-id'));
      const url = $(form).attr('action');

      Swal.fire({
      title: 'Anda Yakin?',
      html: `Menghapus jenis barang <b>${categoryName}</b> juga dapat mempengaruhi data barang terkait.`,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      cancelButtonColor: '#6c757d',
      confirmButtonText: 'Ya, Hapus!',
      cancelButtonText: 'Batal'
      }).then((result) => {
      if (result.isConfirmed) {
        // Ganti form.submit() dengan $.ajax()
        $.ajax({
        url: url,
        type: 'POST',
        data: {
          _token: $('meta[name="csrf-token"]').attr('content'),
          _method: 'DELETE'
        },
        success: function (response) {
          // Hapus baris dari DataTable secara dinamis
          if (typeof table !== 'undefined') {
          table.row(button.closest('tr')).remove().draw(false);
          } else {
          // Fallback jika datatable tidak ada, reload halaman
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
          let errorMessage = 'Terjadi kesalahan saat menghapus data.';
          // PERBAIKAN: Tampilkan pesan error spesifik dari controller
          if (xhr.responseJSON && xhr.responseJSON.error) {
          errorMessage = xhr.responseJSON.error;
          }
          Swal.fire(
          'Gagal!',
          errorMessage,
          'error'
          );
        }
        });
      }
      });
    });
    });
  </script>
@endpush