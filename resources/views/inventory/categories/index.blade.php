@extends('layouts.app')
@section('title', 'Jenis Barang')
@section('content')
  <div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">
      <i class="fas fa-boxes text-primary"></i> Jenis Barang
    </h4>
    <div>
      <a href="{{ route('inventory.categories.create') }}" class="btn btn-outline-primary"
      style="--bs-btn-padding-y: .25rem; --bs-btn-padding-x: .5rem; --bs-btn-font-size: .75rem;">
      <i class="fas fa-plus-circle"></i> Tambah Jenis Barang
      </a>
    </div>
    </div>
    <div class="row">
    <div class="col-12">
      <div class="card shadow-sm border-0">
      <div class="card-body p-4">
        {{-- Alert untuk pesan sukses --}}
        @if (session('success'))
      <div class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert">
      <i class="fas fa-check-circle me-2"></i>
      <div>{{ session('success') }}</div>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
      @endif

        <div class="table-responsive">
        <table class="table table-hover align-middle" id="categories-table" style="width:100%">
          <thead class="table-light">
          <tr>
            <th width="5%">No.</th>
            <th>Nama Jenis</th>
            <th>Tipe</th>
            <th width="20%">Aksi</th>
          </tr>
          </thead>
          <tbody>
          @forelse ($categories as $category)
          <tr>
            <td class="text-center">{{ $loop->iteration }}</td>
            <td>
            <div class="d-flex align-items-center">
            @if($category->type === 'Cat')
          <i class="fas fa-paint-roller text-info me-2"></i>
          @elseif($category->type === 'Keramik')
          <i class="fas fa-border-style text-warning me-2"></i>
          @else
          <i class="fas fa-box text-secondary me-2"></i>
          @endif
            <span>{{ $category->name }}</span>
            </div>
            </td>
            <td>
            <span class="badge 
          @if($category->type === 'Cat') bg-info
        @elseif($category->type === 'Keramik') bg-warning text-dark
        @else bg-secondary
        @endif">
            {{ $category->type }}
            </span>
            </td>
            <td>
            <div class="d-flex gap-2">
            <a href="{{ route('inventory.categories.edit', $category) }}"
            class="btn btn-sm btn-outline-warning" data-bs-toggle="tooltip" title="Edit">
            <i class="fas fa-edit"></i>
            </a>
            <form action="{{ route('inventory.categories.destroy', $category) }}" method="POST"
            onsubmit="return confirm('Apakah Anda yakin ingin menghapus jenis barang ini? Semua barang yang terkait dengan jenis ini akan terhapus jika kategori ini digunakan untuk item generik. Ini tidak berlaku untuk Keramik dan Cat');">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-sm btn-outline-danger" data-bs-toggle="tooltip"
              title="Hapus">
              <i class="fas fa-trash-alt"></i>
            </button>
            </form>
            </div>
            </td>
          </tr>
      @empty
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
    .card {
    border-radius: 10px;
    overflow: hidden;
    }

    .card-header {
    padding: 1.25rem 1.5rem;
    }

    .table th {
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.8rem;
    letter-spacing: 0.5px;
    }

    .badge {
    font-size: 0.75rem;
    padding: 0.35em 0.65em;
    font-weight: 500;
    }

    .btn-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.75rem;
    }

    .alert {
    border-radius: 8px;
    }

    @media (max-width: 768px) {
    .card-header {
      flex-direction: column;
      align-items: flex-start;
      gap: 1rem;
    }

    .btn {
      width: 100%;
    }
    }
  </style>
@endpush

@push('scripts')
  <script>
    $(document).ready(function () {
    // Inisialisasi DataTables
    $('#categories-table').DataTable({
      "paging": true,
      "lengthChange": true,
      "searching": true,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "responsive": true,
      "columnDefs": [
      {
        "orderable": false,
        "searchable": false,
        "targets": [0, 3]
      }
      ],
      "order": [[1, 'asc']],
      "language": {
      "emptyTable": "Tidak ada jenis barang yang ditemukan.",
      "search": "Cari:",
      "lengthMenu": "Tampilkan _MENU_ jenis per halaman",
      "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ jenis",
      "infoEmpty": "Menampilkan 0 sampai 0 dari 0 jenis",
      "paginate": {
        "first": "Pertama",
        "last": "Terakhir",
        "next": "Selanjutnya",
        "previous": "Sebelumnya"
      }
      }
    });

    // Inisialisasi tooltip
    $('[data-bs-toggle="tooltip"]').tooltip();
    });
  </script>
@endpush