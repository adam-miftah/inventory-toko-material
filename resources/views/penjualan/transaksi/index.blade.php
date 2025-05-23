@extends('layouts.app')

@section('title', 'Daftar Transaksi Penjualan')

@section('content')
    <div class="container-fluid">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <div>
                <h4 class=" mb-0 text-gray-800">Daftar Transaksi Penjualan</h4>
            </div>
            <div class="d-flex">
                <a href="{{ route('penjualan.transaksi.create') }}"
                    class="btn btn-outline-primary btn-sm d-flex align-items-center">
                    <i class="fas fa-plus-circle me-2"></i> Transaksi Baru
                </a>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                <div class="d-flex align-items-center">
                    <i class="fas fa-check-circle me-2"></i>
                    <div>{{ session('success') }}</div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                <div class="d-flex align-items-center">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <div>{{ session('error') }}</div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card shadow-sm mb-4">
            <div class="card-body py-3">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label small text-muted mb-1">Tanggal Mulai</label>
                        <input type="date" class="form-control form-control-sm" id="start-date">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small text-muted mb-1">Tanggal Akhir</label>
                        <input type="date" class="form-control form-control-sm" id="end-date">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small text-muted mb-1">Metode Pembayaran</label>
                        <select class="form-select form-select-sm" id="payment-method">
                            <option value="">Semua</option>
                            <option value="cash">Cash</option>
                            <option value="transfer">Transfer</option>
                        </select>
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button class="btn btn-sm btn-outline-primary me-2" id="filter-btn">
                            <i class="fas fa-filter me-1"></i> Filter
                        </button>
                        <button class="btn btn-sm btn-outline-secondary" id="reset-btn">
                            Reset
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm">
            <div
                class="card-header bg-white py-3 d-flex flex-column flex-md-row justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-list-alt me-2"></i> Daftar Transaksi
                </h6>
                <div class="mt-2 mt-md-0">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-light border-0"><i class="fas fa-search"></i></span>
                        <input type="text" id="search-input" class="form-control border-0 bg-light"
                            placeholder="Cari transaksi...">
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0" id="sales-table" style="width:100%">
                        <thead class="bg-light">
                            <tr>
                                <th>No.</th>
                                <th>Nomor Faktur</th>
                                <th>Tanggal</th>
                                <th>Nama Barang</th>
                                <th>Total</th>
                                <th>Pembayaran</th>
                                <th>Kasir</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($sales as $sale)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td>
                                        <span class="fw-bold">{{ $sale->invoice_number }}</span>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span>{{ $sale->sale_date->format('d M Y') }}</span>
                                        </div>
                                    </td>
                                    <td>{{ $sale->items->first()->item_name ?? '-' }}</td>
                                    <td class="fw-bold">{{ 'Rp ' . number_format($sale->grand_total, 0, ',', '.') }}
                                    </td>
                                    <td>
                                        <span class="badge @if ($sale->payment_method == 'cash') bg-success
                                        @else bg-warning text-dark @endif">
                                            {{ ucfirst($sale->payment_method) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="me-2">
                                                <div
                                                    class="avatar-sm bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center">
                                                    {{ substr($sale->user->name, 0, 1) }}
                                                </div>
                                            </div>
                                            <span>{{ $sale->user->name }}</span>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('penjualan.transaksi.show', $sale) }}"
                                            class="btn btn-sm btn-outline-primary" title="Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <button class="btn btn-sm btn-outline-secondary" title="Cetak"
                                            onclick="printInvoice('{{ $sale->id }}')">
                                            <i class="fas fa-print"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4">Tidak ada data transaksi</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer bg-white py-3">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">
                    <div class="mb-2 mb-md-0">
                        Menampilkan <span class="fw-bold">{{ $sales->firstItem() }}</span> sampai <span
                            class="fw-bold">{{ $sales->lastItem() }}</span> dari <span
                            class="fw-bold">{{ $sales->total() }}</span>
                        transaksi
                    </div>
                    <div>
                        {{ $sales->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .avatar-sm {
            width: 28px;
            height: 28px;
            font-size: 0.8rem;
        }

        .table th {
            text-transform: uppercase;
            font-size: 12px;
            letter-spacing: 0.5px;
            border-top: none;

        }

        .table td {
            vertical-align: middle;
            font-size: 12px;
            text-align: start;
        }

        .badge {
            font-weight: 500;
            padding: 0.35em 0.65em;
        }

        .card {
            border-radius: 10px;
            overflow: hidden;
            border: none;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.1);
        }

        .card-header {
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }

        .form-control,
        .form-select {
            border-radius: 6px;
        }

        @media (max-width: 768px) {
            .table-responsive {
                border: 0;
            }

            .table thead {
                display: none;
            }

            .table tr {
                display: block;
                margin-bottom: 1rem;
                border-radius: 8px;
                box-shadow: 0 0 0.75rem rgba(0, 0, 0, 0.05);
            }

            .table td {
                display: flex;
                justify-content: space-between;
                align-items: center;
                border-bottom: 1px solid #f0f0f0;
                padding: 0.75rem;
            }

            .table td:before {
                content: attr(data-label);
                font-weight: 600;
                margin-right: 1rem;
                color: #6c757d;
                flex: 0 0 120px;
            }

            .table td:last-child {
                border-bottom: 0;
            }

            .table td.text-center {
                justify-content: center;
            }

            .table td.text-end {
                justify-content: flex-end;
            }
        }

        /* .pagination-info {
                                                                                                                                                                                                display: none !important;
                                                                                                                                                                                                } */

        .dataTables_length {
            /* display: none !important; */
            margin: 0 15px !important;
        }

        .dataTables_paginate {
            margin: 0 15px !important;
            /* Atur margin kiri-kanan */
            padding: 10px 5px 5px 5px !important;
            border-radius: 4px !important;
        }
    </style>
@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
            // Initialize DataTable with enhanced options
            var table = $('#sales-table').DataTable({
                "dom": '<"top"f>rt<"bottom"lip><"clear">',
                "responsive": true,
                "info": false, // Menghilangkan info "Showing x to y of z entries"
                // "lengthChange": false,
                "language": {
                    "search": "",
                    "searchPlaceholder": "Cari transaksi...",
                    "lengthMenu": "Tampilkan _MENU_ transaksi per halaman",
                    "zeroRecords": "Tidak ada transaksi ditemukan",
                    "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ transaksi",
                    "infoEmpty": "Menampilkan 0 sampai 0 dari 0 transaksi",
                    "infoFiltered": "(disaring dari _MAX_ total transaksi)",
                    "paginate": {
                        "first": "Pertama",
                        "last": "Terakhir",
                        "next": "Selanjutnya",
                        "previous": "Sebelumnya"
                    }
                },
                "columnDefs": [{
                    "orderable": false,
                    "targets": [0, 7],
                    "className": "text-center"
                }, {
                    "orderable": true,
                    "targets": [1, 2, 3, 4, 5, 6],
                    "responsivePriority": 1
                }],
                "order": [[2, 'desc']],
                "drawCallback": function (settings) {
                    // Add data-label attributes for responsive display
                    if ($(window).width() < 768) {
                        $('thead th').each(function (i) {
                            $('tbody td').eq(i).attr('data-label', $(this).text());
                        });
                    }
                },
                "data": @json($sales->items()),
                "columns": [{
                    "data": null,
                    "className": "text-center",
                    "orderable": false,
                    "render": function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                }, {
                    "data": "invoice_number",
                    "className": "text-center"
                }, {
                    "data": "sale_date",
                    "className": "text-center"
                }, {
                    "data": "items.0.item_name",
                    "defaultContent": "-"
                }, {
                    "data": "grand_total",
                    "className": "text-end"
                }, {
                    "data": "payment_method",
                    "className": "text-center",
                    "render": function (data) {
                        var badgeClass = '';
                        if (data === 'cash') {
                            badgeClass = 'bg-success';
                        } else if (data === 'transfer') {
                            badgeClass = 'bg-info';
                        } else {
                            badgeClass = 'bg-warning text-dark';
                        }
                        return '<span class="badge ' + badgeClass + '">' + data.charAt(0).toUpperCase() + data.slice(1) + '</span>';
                    }
                }, {
                    "data": "user.name"
                }, {
                    "data": null,
                    "className": "text-center",
                    "orderable": false,
                    "render": function (data, type, row) {
                        return '<a href="/penjualan/transaksi/' + row.id + '" class="btn btn-sm btn-outline-primary" title="Detail"><i class="fas fa-eye"></i></a>' +
                            '<button class="btn btn-sm btn-outline-secondary" title="Cetak" onclick="printInvoice(\'' + row.id + '\')"><i class="fas fa-print"></i></button>';
                    }
                }]
            });

            // Custom search input
            $('#search-input').keyup(function () {
                table.search($(this).val()).draw();
            });

            // Filter functionality
            $('#filter-btn').click(function () {
                var startDate = $('#start-date').val();
                var endDate = $('#end-date').val();
                var paymentMethod = $('#payment-method').val();

                var url = "{{ route('penjualan.transaksi.index') }}";
                var params = [];

                if (startDate) {
                    params.push("start_date=" + startDate);
                }
                if (endDate) {
                    params.push("end_date=" + endDate);
                }
                if (paymentMethod) {
                    params.push("payment_method=" + paymentMethod);
                }

                if (params.length > 0) {
                    window.location.href = url + "?" + params.join("&");
                } else {
                    window.location.href = url; // Reload tanpa filter jika tidak ada parameter
                }
            });
            // Reset filters
            $('#reset-btn').click(function () {
                $('#start-date').val('');
                $('#end-date').val('');
                $('#payment-method').val('');
                // Trigger filter to reload data
                $('#filter-btn').click();
            });
        });

        function printInvoice(saleId) {
            // Implement print functionality
            window.open('/penjualan/transaksi/' + saleId + '/print', '_blank'); // <--- URL ini harus sesuai dengan rute
        }
    </script>
@endpush