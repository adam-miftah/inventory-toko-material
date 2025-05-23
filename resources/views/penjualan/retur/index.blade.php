@extends('layouts.app')

@section('title', 'Daftar Retur Penjualan')

@section('content')
  <div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">
      <i class="fas fa-undo-alt text-primary me-2"></i>Daftar Retur Penjualan
    </h4>
    <a href="{{ route('penjualan.retur.create') }}" class="btn btn-outline-primary btn-sm">
      <i class="fas fa-plus-circle me-1"></i> Buat Retur Baru
    </a>
    </div>

    @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="fas fa-check-circle me-2"></i>
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
    @if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
    <i class="fas fa-exclamation-circle me-2"></i>
    {{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="card border-0 shadow-sm">
    <div class="card-body p-0">
      <div class="table-responsive">
      <table class="table table-hover mb-0" id="sale-returns-table">
        <thead class="table-light">
        <tr>
          <th width="5%">No.</th>
          <th>Nomor Retur</th>
          <th>Tgl. Retur</th>
          <th>No. Faktur</th>
          <th>Pelanggan</th>
          <th class="text-end">Total Retur</th>
          <th class="text-end">Refund</th>
          <th>Kasir</th>
          <th width="15%" class="text-center">Aksi</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($saleReturns as $return)
      <tr>
        <td class="fw-semibold">{{ $loop->iteration }}</td>
        <td>{{ $return->return_number }}</td>
        <td>
        <span class="text-muted">
        {{ $return->return_date->format('d M Y') }}
        </span>
        <small class="d-block text-muted">{{ $return->return_date->format('H:i') }}</small>
        </td>
        <td>{{ $return->sale ? $return->sale->invoice_number : 'Umum' }}</td>
        <td>{{ $return->sale ? ($return->sale->customer_name ?? 'Umum') : 'N/A' }}</td>
        <td class="text-end fw-semibold">Rp {{ number_format($return->total_returned_amount, 0, ',', '.') }}</td>
        <td class="text-end">
        <span class="fw-semibold text-{{ $return->refund_amount > 0 ? 'success' : 'secondary' }}">
        Rp {{ number_format($return->refund_amount ?? 0, 0, ',', '.') }}
        </span>
        </td>
        <td>{{ $return->user->name }}</td>
        <td class="text-center">
        <div class="d-flex justify-content-center gap-2">
        <a href="{{ route('penjualan.retur.show', $return) }}"
        class="btn btn-sm btn-outline-info rounded-circle" data-bs-toggle="tooltip" title="Detail">
        <i class="fas fa-eye"></i>
        </a>
        <form action="{{ route('penjualan.retur.destroy', $return) }}" method="POST"
        class="d-inline delete-form">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-sm btn-outline-danger rounded-circle" data-bs-toggle="tooltip"
          title="Hapus">
          <i class="fas fa-trash-alt"></i>
        </button>
        </form>
        </div>
        </td>
      </tr>
      @endforeach
        </tbody>
      </table>
      </div>
    </div>
    </div>
  </div>
@endsection

@push('styles')
  <style>
    .card {
    border-radius: 0.5rem;
    overflow: hidden;
    }

    .table {
    margin-bottom: 0;
    }

    .table th {
    border-top: none;
    font-weight: 600;
    font-size: 0.85rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: #6c757d;
    white-space: nowrap;
    }

    .table td {
    vertical-align: middle;
    }

    .badge {
    font-weight: 500;
    padding: 0.35em 0.65em;
    }

    .btn-circle {
    width: 32px;
    height: 32px;
    padding: 0;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    }

    @media (max-width: 767.98px) {
    .table-responsive {
      border: none;
    }

    .table thead {
      display: none;
    }

    .table tr {
      display: block;
      margin-bottom: 1rem;
      border: 1px solid #dee2e6;
      border-radius: 0.25rem;
    }

    .table td {
      display: flex;
      justify-content: space-between;
      align-items: center;
      border-bottom: 1px solid #dee2e6;
      padding-left: 50%;
      position: relative;
    }

    .table td:before {
      content: attr(data-label);
      position: absolute;
      left: 1rem;
      width: calc(50% - 1rem);
      padding-right: 1rem;
      font-weight: 600;
      text-align: left;
    }

    .table td:last-child {
      border-bottom: none;
    }

    .table td .d-flex {
      justify-content: flex-end;
    }
    }
  </style>
@endpush

@push('scripts')
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    $(document).ready(function () {
    // Enable tooltips
    $('[data-bs-toggle="tooltip"]').tooltip();

    // Add data-label attributes for responsive table
    $('#sale-returns-table thead th').each(function (index) {
      $('#sale-returns-table tbody td:nth-child(' + (index + 1) + ')').attr('data-label', $(this).text());
    });

    // Initialize DataTable with proper column count
    $('#sale-returns-table').DataTable({
      "columns": [
      { "width": "5%" }, // No.
      null, // Nomor Retur
      null, // Tgl. Retur
      null, // No. Faktur
      null, // Pelanggan
      { "className": "text-end" }, // Total Retur
      { "className": "text-end" }, // Refund
      null, // Kasir
      {
        "width": "15%",
        "className": "text-center",
        "orderable": false,
        "searchable": false
      } // Aksi
      ],
      "paging": true,
      "lengthChange": true,
      "searching": true,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "responsive": true,
      "language": {
      "search": "Cari:",
      "zeroRecords": "Tidak ada data yang cocok",
      "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
      "infoEmpty": "Menampilkan 0 sampai 0 dari 0 data",
      "paginate": {
        "first": "Pertama",
        "last": "Terakhir",
        "next": "Selanjutnya",
        "previous": "Sebelumnya"
      }
      },
      "order": [[2, 'desc']], // Order by return date (column index 2)
      "drawCallback": function (settings) {
      // Re-add data-labels after table redraw
      $('#sale-returns-table thead th').each(function (index) {
        $('#sale-returns-table tbody td:nth-child(' + (index + 1) + ')').attr('data-label', $(this).text());
      });
      }
    });

    // Delete confirmation with SweetAlert
    $(document).on('submit', '.delete-form', function (e) {
      e.preventDefault();
      var form = this;

      Swal.fire({
      title: 'Konfirmasi Hapus',
      text: "Anda yakin ingin menghapus retur ini? Tindakan ini akan mengembalikan stok barang.",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      cancelButtonColor: '#3085d6',
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