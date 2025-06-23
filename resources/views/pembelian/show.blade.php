@extends('layouts.app')

@section('title', 'Detail Pembelian')

@section('content')
  <div class="container-fluid">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
    <h4 class="mb-3 mb-md-0 fw-bold text-gradient">
      <i class="fas fa-truck-loading me-2"></i>
      Detail Pembelian
    </h4>
    <div class="d-flex gap-2">
      <a href="{{ route('pembelian.index') }}" class="btn btn-secondary btn-sm">
      <i class="fas fa-arrow-left me-1"></i> Kembali
      </a>
    </div>
    </div>

    {{-- KONTEN UTAMA --}}
    <div class="row g-4">
    {{-- KOLOM KIRI: DETAIL ITEM --}}
    <div class="col-lg-8">
      <div class="card shadow-sm border-0 h-100">
      <div class="card-header bg-white p-3">
        <h5 class="mb-0 fw-semibold"><i class="fas fa-boxes me-2 text-primary"></i>Item yang Dibeli</h5>
      </div>
      <div class="card-body p-0">
        @if ($pembelian->items->isNotEmpty())
      <div class="table-responsive">
      <table class="table table-hover align-middle mb-0" id="purchase-items-table">
        <thead class="table-light">
        <tr>
        <th class="ps-3">Produk</th>
        <th class="text-center">Kuantitas</th>
        <th class="text-end">Harga Satuan</th>
        <th class="text-end pe-3">Subtotal</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($pembelian->items as $itemPembelian)
      <tr>
      <td data-label="Produk" class="ps-3">
      <a href="{{ route('inventory.items.show', $itemPembelian->item) }}"
        class="text-dark fw-semibold text-decoration-none">
        {{ $itemPembelian->item_name }}
      </a>
      </td>
      <td data-label="Kuantitas" class="text-center">{{ $itemPembelian->quantity }}</td>
      <td data-label="Harga Satuan" class="text-end">{{ $itemPembelian->unit_price_formatted }}</td>
      <td data-label="Subtotal" class="text-end pe-3">{{ $itemPembelian->subtotal_formatted }}</td>
      </tr>
      @endforeach
        </tbody>
      </table>
      </div>
      @else
      <div class="p-4 text-center text-muted">
      <i class="fas fa-box-open fa-2x mb-2"></i>
      <p>Tidak ada item dalam pembelian ini.</p>
      </div>
      @endif
      </div>
      </div>
    </div>

    {{-- KOLOM KANAN: INFORMASI PEMBELIAN --}}
    <div class="col-lg-4">
      <div class="card shadow-sm border-0">
      <div class="card-header bg-white p-3">
        <h5 class="mb-0 fw-semibold"><i class="fas fa-info-circle me-2 text-primary"></i>Informasi Transaksi</h5>
      </div>
      <div class="card-body p-0">
        <ul class="list-group list-group-flush">
        <li class="list-group-item d-flex justify-content-between align-items-center">
          <span class="fw-semibold text-muted">Supplier</span>
          <a href="{{ route('suppliers.show', $pembelian->supplier) }}"
          class="fw-bold text-decoration-none">{{ $pembelian->supplier->name }}</a>
        </li>
        <li class="list-group-item d-flex justify-content-between align-items-center">
          <span class="fw-semibold text-muted">Tanggal</span>
          <span>{{ $pembelian->purchase_date->format('d M Y') }}</span>
        </li>
        <li class="list-group-item d-flex justify-content-between align-items-center">
          <span class="fw-semibold text-muted">Kasir</span>
          <span>{{ $pembelian->user->name }}</span>
        </li>
        <li class="list-group-item d-flex justify-content-between align-items-center bg-light">
          <h6 class="mb-0 fw-bold">Total Pembelian</h6>
          <h6 class="mb-0 fw-bold text-primary">{{ $pembelian->total_amount_formatted }}</h6>
        </li>
        </ul>
      </div>
      @if($pembelian->notes)
      <div class="card-footer bg-white">
      <p class="mb-1 fw-semibold text-muted small">Catatan:</p>
      <p class="mb-0 fst-italic">{{ $pembelian->notes }}</p>
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

    .table th {
    font-weight: 600;
    font-size: 0.8rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    }

    .table td {
    font-size: 0.9rem;
    }

    /* --- Tampilan Kartu Responsif untuk Tabel Item --- */
    @media (max-width: 767.98px) {
    #purchase-items-table thead {
      display: none;
      /* Sembunyikan header tabel */
    }

    #purchase-items-table tr {
      display: block;
      margin-bottom: 1rem;
      border: 1px solid #dee2e6;
      border-radius: .5rem;
      box-shadow: 0 .125rem .25rem rgba(0, 0, 0, .075);
      padding: 0.5rem;
    }

    #purchase-items-table td {
      display: flex;
      align-items: center;
      justify-content: space-between;
      text-align: right;
      /* Data rata kanan */
      padding: 0.5rem;
      border: none;
    }

    #purchase-items-table td:first-child {
      padding-top: 0.5rem;
      /* Beri padding atas pada item pertama */
    }

    #purchase-items-table td:last-child {
      padding-bottom: 0.5rem;
    }

    /* Style untuk label dari data-label */
    #purchase-items-table td::before {
      content: attr(data-label);
      font-weight: 600;
      color: #6c757d;
      margin-right: 1rem;
      text-align: left;
      /* Label rata kiri */
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
    const purchaseId = button.data('id');
    const csrfToken = $('meta[name="csrf-token"]').attr('content');

    Swal.fire({
      title: 'Anda Yakin?',
      html: `Menghapus pembelian <b>#${purchaseId}</b> akan mengurangi stok barang yang sebelumnya ditambahkan. Aksi ini tidak dapat dibatalkan.`,
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
          // Arahkan kembali ke halaman index setelah sukses
          window.location.href = "{{ route('pembelian.index') }}";
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