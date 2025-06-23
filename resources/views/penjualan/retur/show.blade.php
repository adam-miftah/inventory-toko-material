@extends('layouts.app')

@section('title', 'Detail Retur Penjualan')

@section('content')
    <div class="container-fluid">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
            <h4 class="mb-3 mb-md-0 fw-bold text-gradient">
                <i class="fas fa-undo-alt me-2"></i>
                Detail Retur #{{ $retur->return_number }}
            </h4>
            <div class="d-flex gap-2">
                <a href="{{ route('penjualan.retur.index') }}" class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left me-1"></i> Kembali
                </a>
            </div>
        </div>

        {{-- KONTEN UTAMA --}}
        <div class="row g-4">
            <div class="col-lg-5">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header bg-white p-3">
                        <h5 class="mb-0 fw-semibold"><i class="fas fa-info-circle me-2 text-primary"></i>Informasi Retur
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span class="text-muted">Ref. Penjualan</span>
                                @if ($retur->sale)
                                    <a href="{{ route('penjualan.transaksi.show', $retur->sale) }}"
                                        class="fw-bold text-decoration-none">#{{ $retur->sale->invoice_number }}</a>
                                @else
                                    <span class="fw-semibold">N/A</span>
                                @endif
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span class="text-muted">Tanggal Retur</span>
                                <span
                                    class="fw-semibold">{{ $retur->return_date ? $retur->return_date->isoFormat('DD MMMM YYYY') : 'N/A' }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span class="text-muted">Pelanggan</span>
                                <span class="fw-semibold">{{ $retur->sale->customer_name ?? 'Umum' }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span class="text-muted">Diproses oleh</span>
                                <span class="fw-semibold">{{ $retur->user->name ?? 'N/A' }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center bg-light">
                                <h6 class="mb-0 fw-bold">Total Refund</h6>
                                <h6 class="mb-0 fw-bold text-danger">Rp
                                    {{ number_format($retur->refund_amount ?? 0, 0, ',', '.') }}</h6>
                            </li>
                        </ul>
                    </div>
                    @if($retur->notes)
                        <div class="card-footer bg-white">
                            <p class="mb-1 fw-semibold text-muted small">Alasan Retur:</p>
                            <p class="mb-0 fst-italic">{{ $retur->notes }}</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- KOLOM KANAN: ITEM YANG DIRETUR --}}
            <div class="col-lg-7">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header bg-white p-3">
                        <h5 class="mb-0 fw-semibold"><i class="fas fa-boxes me-2 text-primary"></i>Item yang Dikembalikan
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        @if ($retur->items->isNotEmpty())
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0" id="retur-item-table">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="ps-3">Produk</th>
                                            <th class="text-center">Qty</th>
                                            <th class="text-end">Harga Satuan</th>
                                            <th class="text-end pe-3">Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($retur->items as $item)
                                            <tr>
                                                <td data-label="Produk" class="ps-3">{{ $item->item->name ?? 'N/A' }}</td>
                                                <td data-label="Qty" class="text-center">{{ $item->quantity }}</td>
                                                <td data-label="Harga" class="text-end">Rp
                                                    {{ number_format($item->price_per_unit, 0, ',', '.') }}</td>
                                                <td data-label="Subtotal" class="text-end pe-3">Rp
                                                    {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="p-4 text-center text-muted">
                                <i class="fas fa-box-open fa-2x mb-2"></i>
                                <p>Tidak ada item dalam retur ini.</p>
                            </div>
                        @endif
                    </div>
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

        /* Tampilan Kartu Responsif untuk Tabel */
        @media (max-width: 767.98px) {
            #retur-item-table thead {
                display: none;
            }

            #retur-item-table tr {
                display: block;
                border-bottom: 1px solid #dee2e6;
            }

            #retur-item-table td {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 0.75rem 1rem;
                border: none;
            }

            #retur-item-table td::before {
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
            const returnName = button.data('name');
            const csrfToken = $('meta[name="csrf-token"]').attr('content');

            Swal.fire({
                title: 'Anda Yakin?',
                html: `Menghapus retur <b>#${returnName}</b> akan mengurangi stok barang yang sebelumnya diretur. Aksi ini tidak dapat dibatalkan.`,
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
                                window.location.href = "{{ route('retur-pembelian.index') }}";
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