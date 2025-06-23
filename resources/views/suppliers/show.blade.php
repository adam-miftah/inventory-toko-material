@extends('layouts.app')

@section('title', 'Detail Supplier')

@section('content')
  <div class="container-fluid">
    {{-- HEADER HALAMAN DENGAN TOMBOL AKSI --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
    <h4 class="mb-3 mb-md-0 fw-bold text-gradient">
      <i class="fas fa-user-tie me-2"></i>
      Detail Supplier
    </h4>
    <div class="d-flex gap-2">
      <a href="{{ route('suppliers.index') }}" class="btn btn-secondary btn-sm">
      <i class="fas fa-arrow-left me-1"></i> Kembali
      </a>
    </div>
    </div>

    {{-- KONTEN UTAMA --}}
    <div class="row g-4">
    {{-- KOLOM KIRI: INFORMASI KONTAK --}}
    <div class="col-lg-5">
      <div class="card shadow-sm border-0 h-100">
      <div class="card-header bg-white p-3 d-flex align-items-center">
        <i class="fas fa-id-card-alt fs-4 text-primary me-3"></i>
        <div>
        <h5 class="mb-0 fw-bold">{{ $supplier->name }}</h5>
        </div>
      </div>
      <div class="card-body">
        <ul class="list-unstyled">
        <li class="d-flex mb-3">
          <i class="fas fa-user fa-fw me-3 mt-1 text-muted"></i>
          <div>
          <span class="fw-semibold">Kontak Person</span>
          <p class="mb-0">{{ $supplier->contact_person ?? '-' }}</p>
          </div>
        </li>
        <li class="d-flex mb-3">
          <i class="fas fa-phone-alt fa-fw me-3 mt-1 text-muted"></i>
          <div>
          <span class="fw-semibold">Telepon</span>
          <p class="mb-0">{{ $supplier->phone ?? '-' }}</p>
          </div>
        </li>
        <li class="d-flex mb-3">
          <i class="fas fa-envelope fa-fw me-3 mt-1 text-muted"></i>
          <div>
          <span class="fw-semibold">Email</span>
          <p class="mb-0">{{ $supplier->email ?? '-' }}</p>
          </div>
        </li>
        <li class="d-flex">
          <i class="fas fa-map-marker-alt fa-fw me-3 mt-1 text-muted"></i>
          <div>
          <span class="fw-semibold">Alamat</span>
          <p class="mb-0">{{ $supplier->address ?? '-' }}</p>
          </div>
        </li>
        </ul>
      </div>
      <div class="card-footer bg-light text-muted small py-2">
        Terakhir diperbarui: {{ $supplier->updated_at->diffForHumans() }}
      </div>
      </div>
    </div>

    {{-- KOLOM KANAN: RIWAYAT PEMBELIAN BARANG --}}
    <div class="col-lg-7">
      <div class="card shadow-sm border-0 h-100">
      <div class="card-header bg-white p-3">
        <h5 class="mb-0 fw-semibold"><i class="fas fa-history me-2 text-primary"></i>Riwayat Barang Dibeli</h5>
      </div>
      <div class="card-body p-0">
        @php
      // Menggabungkan semua item dari semua pembelian menjadi satu list
      // lalu diurutkan berdasarkan tanggal pembelian terbaru
      $allItems = $supplier->purchases->flatMap(function ($purchase) {
      return $purchase->items->map(function ($item) use ($purchase) {
      $item->purchase_date_context = $purchase->purchase_date;
      return $item;
      });
      })->sortByDesc('purchase_date_context');
    @endphp

        @if ($allItems->isNotEmpty())
      <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead class="table-light">
        <tr>
        <th class="ps-3">Nama Barang</th>
        <th>Tanggal</th>
        <th class="text-center">Qty</th>
        <th class="text-end pe-3">Subtotal</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($allItems->take(10) as $pembelianItem)
      <tr>
      <td data-label="Barang" class="ps-3">
      <a href="{{ route('inventory.items.show', $pembelianItem->item) }}"
        class="text-dark fw-semibold text-decoration-none">
        {{ $pembelianItem->item->name }}
      </a>
      <small class="d-block text-muted">Ref: #{{ $pembelianItem->pembelian_id }}</small>
      </td>
      <td data-label="Tanggal">{{ $pembelianItem->purchase_date_context->format('d M Y') }}</td>
      <td data-label="Qty" class="text-center">{{ $pembelianItem->quantity }}</td>
      <td data-label="Subtotal" class="text-end pe-3">{{ $pembelianItem->subtotal_formatted }}</td>
      </tr>
      @endforeach
        </tbody>
      </table>
      </div>
      @else
      <div class="p-4 text-center text-muted">
      <i class="fas fa-folder-open fa-2x mb-2"></i>
      <p>Belum ada riwayat pembelian dari supplier ini.</p>
      </div>
      @endif
      </div>
      @if($allItems->count() > 10)
      <div class="card-footer bg-white text-center">
      <a href="#" class="btn btn-sm btn-outline-primary">Lihat Semua Riwayat</a>
      </div>
    @endif
      </div>
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

    .page-title {
    font-size: 1.5rem;
    }

    .table th {
    font-weight: 600;
    font-size: 0.8rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    }

    /* Tampilan Kartu Responsif untuk Tabel Riwayat */
    @media (max-width: 767.98px) {
    table thead {
      display: none;
    }

    table tr {
      display: block;
      border-bottom: 1px solid #dee2e6;
    }

    table td {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 0.75rem 1rem;
      border: none;
    }

    table td::before {
      content: attr(data-label);
      font-weight: 600;
      color: #6c757d;
      margin-right: 1rem;
    }
    }
  </style>
@endpush

@push('scripts')
  <script>
    $(document).on('click', '.delete-btn', function (e) {
    e.preventDefault();

    const button = $(this);
    const url = button.data('url');
    const supplierName = button.data('name');
    const csrfToken = $('meta[name="csrf-token"]').attr('content');

    Swal.fire({
      title: 'Anda Yakin?',
      html: `Menghapus supplier <b>${supplierName}</b> mungkin akan mempengaruhi data pembelian terkait.`,
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
        Swal.fire(
          'Berhasil Dihapus!',
          response.success,
          'success'
        ).then(() => {
          window.location.href = "{{ route('suppliers.index') }}";
        });
        },
        error: function (xhr) {
        let errorMessage = 'Terjadi kesalahan saat menghapus data.';
        if (xhr.responseJSON && xhr.responseJSON.error) {
          errorMessage = xhr.responseJSON.error;
        }
        Swal.fire('Gagal!', errorMessage, 'error');
        }
      });
      }
    });
    });
  </script>
@endpush