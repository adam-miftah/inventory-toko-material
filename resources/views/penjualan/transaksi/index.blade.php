@extends('layouts.app')

@section('title', 'Daftar Transaksi Penjualan')

@section('content')
    <div class="container-fluid">
        {{-- Notifikasi Session --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- Card Utama --}}
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white p-3">
                <div class="d-flex justify-content-between align-items-center flex-wrap">
                    <h4 class="mb-0 fw-bold text-gradient">
                        <i class="fas fa-cash-register me-2"></i>Daftar Transaksi
                    </h4>
                    <a href="{{ route('penjualan.transaksi.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus-circle me-1"></i> Transaksi Baru
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
                                placeholder="Cari di semua kolom...">
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 @if($sales->isEmpty()) is-empty @endif"
                        id="sales-table" style="width:100%">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center" width="5%">No.</th>
                                <th class="text-start">Nomor Faktur</th>
                                <th class="text-start">Tanggal</th>
                                <th class="text-start">Pelanggan</th>
                                <th class="text-end">Total</th>
                                <th class="text-center">Pembayaran</th>
                                <th class="text-start">Kasir</th>
                                <th class="text-center" width="10%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($sales as $sale)
                                <tr>
                                    {{-- Nomor akan diisi oleh DataTables --}}
                                    <td data-label="No." class="text-center fw-semibold"></td>
                                    <td data-label="Faktur">
                                        <a href="{{ route('penjualan.transaksi.show', $sale) }}"
                                            class="fw-bold text-primary text-decoration-none">
                                            {{ $sale->invoice_number }}
                                        </a>
                                    </td>
                                    <td data-label="Tanggal" data-order="{{ $sale->sale_date->timestamp }}">
                                        <span class="text-nowrap">{{ $sale->sale_date->isoFormat('DD MMM YY') }}</span>
                                        <small class="d-block text-muted">{{ $sale->sale_date->format('H:i') }}</small>
                                    </td>
                                    <td data-label="Pelanggan">{{ $sale->customer_name ?? 'Umum' }}</td>
                                    <td data-label="Total" class="text-end fw-bold" data-order="{{ $sale->grand_total }}">
                                        Rp {{ number_format($sale->grand_total, 0, ',', '.') }}
                                    </td>
                                    <td data-label="Pembayaran" class="text-center">
                                        @php $badgeClass = ($sale->payment_method == 'cash') ? 'success' : 'info'; @endphp
                                        <span
                                            class="badge bg-{{$badgeClass}}-subtle text-{{$badgeClass}}-emphasis border border-{{$badgeClass}}-subtle rounded-pill">
                                            <i
                                                class="fas fa-{{ $badgeClass == 'success' ? 'money-bill-wave' : 'credit-card' }} me-1"></i>
                                            {{ ucfirst($sale->payment_method) }}
                                        </span>
                                    </td>
                                    <td data-label="Kasir">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm me-2" data-bs-toggle="tooltip"
                                                title="{{ $sale->user->name }}">
                                                {{ substr($sale->user->name, 0, 1) }}
                                            </div>
                                            <span class="d-none d-lg-inline">{{ $sale->user->name }}</span>
                                        </div>
                                    </td>
                                    <td data-label="Aksi" class="text-center">
                                        <div class="d-flex justify-content-center gap-2">
                                            <a href="{{ route('penjualan.transaksi.show', $sale) }}"
                                                class="btn btn-sm btn-outline-info rounded-circle" data-bs-toggle="tooltip"
                                                title="Lihat Detail">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <button class="btn btn-sm btn-outline-secondary rounded-circle"
                                                onclick="printInvoice('{{ $sale->id }}')" data-bs-toggle="tooltip"
                                                title="Cetak Struk">
                                                <i class="fas fa-print"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5 text-muted">
                                        <i class="fas fa-folder-open fa-3x mb-3"></i>
                                        <p class="mb-0">Tidak ada data transaksi yang ditemukan.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            {{-- PERBAIKAN: Footer paginasi Laravel dihapus karena tidak lagi diperlukan --}}
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

        /* Tampilan Kartu Responsif untuk Tabel */
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
                align-items: center;
                justify-content: space-between;
                text-align: right;
                border-bottom: 1px solid #f0f0f0;
                padding: 0.75rem 1rem;
            }

            .table td:last-child {
                border-bottom: 0;
            }

            .table td::before {
                content: attr(data-label);
                font-weight: 600;
                color: #6c757d;
                margin-right: 1rem;
                text-align: left;
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        function printInvoice(saleId) {
            const printUrl = "{{ url('/penjualan/transaksi') }}/" + saleId + "/print";
            window.open(printUrl, '_blank', 'width=800,height=600');
        }

        $(document).ready(function () {
            var tableElement = $('#sales-table:not(.is-empty)');
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
                        zeroRecords: "Tidak ada transaksi yang cocok.",
                        info: "Menampilkan _START_ - _END_ dari _TOTAL_ transaksi",
                        infoEmpty: "Menampilkan 0 transaksi",
                        paginate: { next: "›", previous: "‹" }
                    },
                    columnDefs: [
                        {
                            searchable: false, orderable: false, targets: 0,
                            render: function (data, type, row, meta) {
                                return meta.row + meta.settings._iDisplayStart + 1;
                            }
                        },
                        { orderable: false, targets: 7 }
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
        });
    </script>
@endpush