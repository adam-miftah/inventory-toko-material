@extends('layouts.app')

@section('title', 'Daftar Barang')

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
                <i class="fas fa-exclamation-triangle me-2"></i><strong>Error:</strong><br>{!! nl2br(e(session('error'))) !!}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- Card Utama --}}
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white p-3">
                <div class="d-flex justify-content-between align-items-center flex-wrap">
                    <h4 class="mb-0 fw-bold text-gradient"><i class="fas fa-boxes me-2"></i>Daftar Barang</h4>
                    <div class="ms-auto">
                        <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#importModal"><i
                                class="fas fa-file-import me-1"></i> Import dari Excel</button>
                        <a href="{{ route('inventory.items.create') }}" class="btn btn-primary btn-sm"><i
                                class="fas fa-plus-circle me-1"></i> Tambah Barang Baru</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                {{-- Kontrol Pencarian --}}
                <div class="mb-4">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="fas fa-search text-muted"></i></span>
                        <input type="text" class="form-control border-start-0" id="search_input"
                            placeholder="Cari barang di kategori yang aktif...">
                    </div>
                </div>

                {{-- Navigasi Tab untuk Kategori --}}
                <ul class="nav nav-tabs nav-bordered mb-3" id="categoryTabs" role="tablist">
                    <li class="nav-item" role="presentation"><a class="nav-link active" data-bs-toggle="tab"
                            href="#general-pane"><i class="fas fa-box me-1 d-none d-sm-inline"></i> Barang Umum</a></li>
                    <li class="nav-item" role="presentation"><a class="nav-link" data-bs-toggle="tab" href="#cat-pane"><i
                                class="fas fa-paint-roller me-1 d-none d-sm-inline"></i> Cat</a></li>
                    <li class="nav-item" role="presentation"><a class="nav-link" data-bs-toggle="tab"
                            href="#keramik-pane"><i class="fas fa-border-style me-1 d-none d-sm-inline"></i> Keramik</a>
                    </li>
                    <li class="nav-item" role="presentation"><a class="nav-link" data-bs-toggle="tab" href="#luar-pane"><i
                                class="fas fa-shipping-fast me-1 d-none d-sm-inline"></i> Barang Luar</a></li>
                </ul>

                {{-- Konten Tab --}}
                <div class="tab-content" id="categoryTabContent">
                    {{-- Pane untuk Barang Umum --}}
                    <div class="tab-pane fade show active" id="general-pane" role="tabpanel">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0 @if($generalItems->isEmpty()) is-empty @endif"
                                id="general-items-table" style="width:100%;">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-center" width="5%">No</th>
                                        <th>Nama Produk</th>
                                        <th>Kategori</th>
                                        <th class="text-end">Modal</th>
                                        <th class="text-end">Jual</th>
                                        <th class="text-center">Stok</th>
                                        <th>Satuan</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($generalItems as $item)
                                        <tr>
                                            <td class="text-center fw-semibold" data-label="#">{{ $loop->iteration }}</td>
                                            <td data-label="Produk">{{ $item->name }}</td>
                                            <td data-label="Kategori">
                                                <span
                                                    class="badge bg-info-subtle text-info-emphasis">{{ $item->category->name ?? 'N/A' }}</span>
                                            </td>
                                            {{-- PERBAIKAN: Tampilkan harga modal --}}
                                            <td class="text-end" data-label="Modal">Rp
                                                {{ number_format($item->purchase_price ?? 0, 0, ',', '.') }}
                                            </td>
                                            <td class="text-end fw-semibold" data-label="Jual">Rp
                                                {{ number_format($item->price, 0, ',', '.') }}
                                            </td>
                                            <td class="text-center" data-label="Stok">
                                                <span
                                                    class="badge rounded-pill bg-{{ $item->stock > 10 ? 'success' : ($item->stock > 0 ? 'warning' : 'danger') }}">{{ $item->stock }}</span>
                                            </td>
                                            <td data-label="Satuan">{{ $item->unit ?? '-' }}</td>
                                            <td class="text-center" data-label="Aksi">
                                                <div class="d-flex justify-content-center gap-2">
                                                    <a href="{{ route('inventory.items.show', $item->id) }}"
                                                        class="btn btn-sm btn-info " data-bs-toggle="tooltip" title="Detail"><i
                                                            class="fas fa-eye"></i></a>
                                                    <a href="{{ route('inventory.items.edit', $item->id) }}"
                                                        class="btn btn-sm btn-warning " data-bs-toggle="tooltip" title="Edit"><i
                                                            class="fas fa-edit"></i></a>
                                                    <form action="{{ route('inventory.items.destroy', $item->id) }}"
                                                        method="POST" class="d-inline delete-form">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button" class="btn btn-sm btn-danger  delete-btn"
                                                            data-item-name="{{ $item->name }}" data-bs-toggle="tooltip"
                                                            title="Hapus">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            {{-- PERBAIKAN: Colspan disesuaikan --}}
                                            <td colspan="8" class="text-center py-5 text-muted">
                                                <i class="fas fa-box-open fa-3x mb-3"></i>
                                                <p class="mb-0">Tidak ada data barang umum.</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Pane untuk Cat --}}
                    <div class="tab-pane fade" id="cat-pane" role="tabpanel">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0 @if($catItems->isEmpty()) is-empty @endif"
                                id="cat-items-table" style="width:100%;">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-center" width="5%">No</th>
                                        <th>Nama Produk</th>
                                        <th>Jenis</th>
                                        <th>Warna</th>
                                        <th>Kode</th>
                                        <th>Volume</th>
                                        <th class="text-end">Modal</th>
                                        <th class="text-end">Jual</th>
                                        <th class="text-center">Stok</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($catItems as $item)
                                        <tr>
                                            <td class="text-center fw-semibold" data-label="#">{{ $loop->iteration }}</td>
                                            <td data-label="Produk">{{ $item->name }}</td>
                                            <td data-label="Jenis">{{ $item->paint_type ?? '-' }}</td>
                                            <td data-label="Warna">{{ $item->color_name ?? '-' }}</td>
                                            <td data-label="Kode">@if($item->color_code)<span
                                            class="badge bg-light text-dark border">{{ $item->color_code }}</span>@else
                                                    - @endif</td>
                                            <td data-label="Volume">{{ $item->volume ?? '-' }}</td>
                                            <td class="text-end" data-label="Modal">Rp
                                                {{ number_format($item->purchase_price ?? 0, 0, ',', '.') }}
                                            </td>
                                            <td class="text-end fw-semibold" data-label="Jual">Rp
                                                {{ number_format($item->price, 0, ',', '.') }}
                                            </td>
                                            <td class="text-center" data-label="Stok"><span
                                                    class="badge rounded-pill bg-{{ $item->stock > 10 ? 'success' : ($item->stock > 0 ? 'warning' : 'danger') }}">{{ $item->stock }}</span>
                                            </td>
                                            <td class="text-center" data-label="Aksi">
                                                <div class="d-flex justify-content-center gap-2">
                                                    <a href="{{ route('inventory.items.show', $item->id) }}"
                                                        class="btn btn-sm btn-info " data-bs-toggle="tooltip" title="Detail"><i
                                                            class="fas fa-eye"></i></a>
                                                    <a href="{{ route('inventory.items.edit', $item->id) }}"
                                                        class="btn btn-sm btn-warning " data-bs-toggle="tooltip" title="Edit"><i
                                                            class="fas fa-edit"></i></a>
                                                    <form action="{{ route('inventory.items.destroy', $item->id) }}"
                                                        method="POST" class="d-inline delete-form">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button" class="btn btn-sm btn-danger  delete-btn"
                                                            data-item-name="{{ $item->name }}" data-bs-toggle="tooltip"
                                                            title="Hapus">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="10" class="text-center py-5 text-muted"><i
                                                    class="fas fa-paint-roller fa-3x mb-3"></i>
                                                <p class="mb-0">Tidak ada data barang cat.</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Pane untuk Keramik (sudah benar) --}}
                    <div class="tab-pane fade" id="keramik-pane" role="tabpanel">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0 @if($keramikItems->isEmpty()) is-empty @endif"
                                id="keramik-items-table" style="width:100%;">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-center" width="5%">No</th>
                                        <th>Nama Produk</th>
                                        <th>Ukuran</th>
                                        <th class="text-end">Modal</th>
                                        <th class="text-end">Jual</th>
                                        <th class="text-center">Stok</th>
                                        <th>Satuan</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($keramikItems as $item)
                                        <tr>
                                            <td class="text-center fw-semibold" data-label="#">{{ $loop->iteration }}</td>
                                            <td data-label="Produk">{{ $item->name }}</td>
                                            <td data-label="Ukuran">{{ $item->size ?? '-' }}</td>
                                            <td class="text-end" data-label="Modal">Rp
                                                {{ number_format($item->purchase_price ?? 0, 0, ',', '.') }}
                                            </td>
                                            <td class="text-end fw-semibold" data-label="Jual">Rp
                                                {{ number_format($item->price, 0, ',', '.') }}
                                            </td>
                                            <td class="text-center" data-label="Stok"><span
                                                    class="badge rounded-pill bg-{{ $item->stock > 10 ? 'success' : ($item->stock > 0 ? 'warning' : 'danger') }}">{{ $item->stock }}</span>
                                            </td>
                                            <td data-label="Satuan">{{ $item->unit ?? '-' }}</td>
                                            <td class="text-center" data-label="Aksi">
                                                <div class="d-flex justify-content-center gap-2">
                                                    <a href="{{ route('inventory.items.show', $item->id) }}"
                                                        class="btn btn-sm btn-info " data-bs-toggle="tooltip" title="Detail"><i
                                                            class="fas fa-eye"></i></a>
                                                    <a href="{{ route('inventory.items.edit', $item->id) }}"
                                                        class="btn btn-sm btn-warning " data-bs-toggle="tooltip" title="Edit"><i
                                                            class="fas fa-edit"></i></a>
                                                    <form action="{{ route('inventory.items.destroy', $item->id) }}"
                                                        method="POST" class="d-inline delete-form">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button" class="btn btn-sm btn-danger  delete-btn"
                                                            data-item-name="{{ $item->name }}" data-bs-toggle="tooltip"
                                                            title="Hapus">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center py-5 text-muted"><i
                                                    class="fas fa-border-style fa-3x mb-3"></i>
                                                <p class="mb-0">Tidak ada data barang keramik.</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Pane untuk Barang Luar --}}
                    <div class="tab-pane fade" id="luar-pane" role="tabpanel">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0 @if($luarItems->isEmpty()) is-empty @endif"
                                id="luar-items-table" style="width:100%;">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-center" width="5%">No</th>
                                        <th>Nama Produk</th>
                                        <th class="text-end">Modal</th>
                                        <th class="text-end">Jual</th>
                                        <th class="text-center">Stok</th>
                                        <th>Satuan</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($luarItems as $item)
                                        <tr>
                                            <td class="text-center fw-semibold" data-label="#">{{ $loop->iteration }}</td>
                                            <td data-label="Produk">{{ $item->name }}</td>
                                            <td class="text-end" data-label="Modal">Rp
                                                {{ number_format($item->purchase_price ?? 0, 0, ',', '.') }}
                                            </td>
                                            <td class="text-end fw-semibold" data-label="Jual">Rp
                                                {{ number_format($item->price, 0, ',', '.') }}
                                            </td>
                                            <td class="text-center" data-label="Stok"><span
                                                    class="badge rounded-pill bg-{{ $item->stock > 10 ? 'success' : ($item->stock > 0 ? 'warning' : 'danger') }}">{{ $item->stock }}</span>
                                            </td>
                                            <td data-label="Satuan">{{ $item->unit ?? '-' }}</td>
                                            <td class="text-center" data-label="Aksi">
                                                <div class="d-flex justify-content-center gap-2">
                                                    <a href="{{ route('inventory.items.show', $item->id) }}"
                                                        class="btn btn-sm btn-info " data-bs-toggle="tooltip" title="Detail"><i
                                                            class="fas fa-eye"></i></a>
                                                    <a href="{{ route('inventory.items.edit', $item->id) }}"
                                                        class="btn btn-sm btn-warning " data-bs-toggle="tooltip" title="Edit"><i
                                                            class="fas fa-edit"></i></a>
                                                    <form action="{{ route('inventory.items.destroy', $item->id) }}"
                                                        method="POST" class="d-inline delete-form">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button" class="btn btn-sm btn-danger  delete-btn"
                                                            data-item-name="{{ $item->name }}" data-bs-toggle="tooltip"
                                                            title="Hapus">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            {{-- PERBAIKAN: Colspan disesuaikan --}}
                                            <td colspan="7" class="text-center py-5 text-muted"><i
                                                    class="fas fa-shipping-fast fa-3x mb-3"></i>
                                                <p class="mb-0">Tidak ada data barang luar.</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Import Excel (tidak ada perubahan, disertakan untuk kelengkapan) --}}
    <div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="importModalLabel">Import Data Barang</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <form action="{{ route('inventory.items.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="category_type" class="form-label">Pilih Kategori untuk Diimpor</label>
                            <select class="form-select" id="category_type" name="category_type" required>
                                <option value="general">Barang Umum</option>
                                <option value="cat">Cat</option>
                                <option value="keramik">Keramik</option>
                                <option value="luar">Barang Luar</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="file" class="form-label">File Excel</label>
                            <input class="form-control" type="file" id="file" name="file" accept=".xlsx,.xls" required>
                            <div class="form-text">
                                Format file harus .xlsx atau .xls.
                                <a href="#" id="downloadTemplate">Download template untuk Barang Umum</a>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-file-import me-2"></i>Import
                        </button>
                    </div>
                </form>
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

        .nav-bordered .nav-link {
            border: none;
            border-bottom: 2px solid transparent;
            color: #6c757d;
            font-weight: 500;
        }

        .nav-bordered .nav-link.active,
        .nav-bordered .nav-link:hover {
            border-bottom-color: var(--primary);
            color: var(--primary);
            background-color: transparent;
        }

        .table th {
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 600;
        }

        .table td {
            vertical-align: middle;
            font-size: 0.875rem;
        }

        .badge.rounded-pill {
            padding: 0.4em 0.8em;
            font-size: 0.75rem;
            font-weight: 600;
        }

        /* --- Tampilan Kartu Responsif untuk Tabel --- */
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
                flex-shrink: 0;
            }

            .table td:last-child {
                border-bottom: 0;
            }
        }
    </style>
@endpush
@push('scripts')
    {{-- SweetAlert2 dan DataTables harus sudah di-load di layout utama Anda --}}
    <script>
        $(document).ready(function () {
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });

            let activeDataTable = null;

            @if(session('active_tab'))
                let targetTabId = '{{ session('active_tab') }}' + '-pane';
                let tabLink = $('#categoryTabs a[href="#' + targetTabId + '"]');
                if (tabLink.length) {
                    $('#categoryTabs .nav-link.active').removeClass('active');
                    $('#categoryTabContent .tab-pane.active').removeClass('show active');
                    tabLink.addClass('active');
                    $('#' + targetTabId).addClass('show active');
                }
            @endif

                function initDataTable(tableId) {
                    if (!tableId || $(`#${tableId}`).hasClass('is-empty')) {
                        if (activeDataTable) {
                            activeDataTable.destroy();
                            activeDataTable = null;
                        }
                        return;
                    }

                    if ($.fn.DataTable.isDataTable(`#${tableId}`)) {
                        activeDataTable = $(`#${tableId}`).DataTable();
                        return;
                    }

                    activeDataTable = $(`#${tableId}`).DataTable({
                        paging: true,
                        lengthChange: false,
                        searching: true,
                        ordering: true,
                        info: true,
                        autoWidth: false,
                        responsive: true,
                        dom: '<"row"<"col-sm-12"t>><"row mt-3"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
                        language: {
                            search: "Cari:", zeroRecords: "Tidak ditemukan data yang cocok", info: "Menampilkan _START_ - _END_ dari _TOTAL_ data", infoEmpty: "Menampilkan 0 data", infoFiltered: "(difilter dari _MAX_ total data)",
                            paginate: { first: "Awal", last: "Akhir", next: "›", previous: "‹" }
                        },
                        columnDefs: [
                            {
                                searchable: false, orderable: false, targets: 0,
                                render: (data, type, row, meta) => meta.row + meta.settings._iDisplayStart + 1
                            },
                            { orderable: false, targets: -1 }
                        ]
                    });
                }

            function setupTable(selector) {
                const tableId = $(selector).find('table').attr('id');
                if (activeDataTable && $.fn.DataTable.isDataTable(`#${activeDataTable.table().node().id}`) && activeDataTable.table().node().id !== tableId) {
                    activeDataTable.destroy();
                }
                initDataTable(tableId);
            }

            setupTable('#categoryTabContent .tab-pane.active');

            $('#categoryTabs a[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
                const newTabPaneId = $(e.target).attr('href');
                setupTable(newTabPaneId);
                $('#search_input').val('').trigger('keyup');
            });

            $('#search_input').on('keyup', function () {
                if (activeDataTable) {
                    activeDataTable.search(this.value).draw();
                }
            });

            $(document).on('click', '.delete-btn', function (e) {
                e.preventDefault();
                const button = $(this);
                const form = button.closest('form');
                const itemName = button.data('item-name');
                const url = form.attr('action');

                Swal.fire({
                    title: 'Anda Yakin?',
                    html: `Anda akan menghapus barang: <br><b>${itemName}</b>`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Hapus Saja!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: url,
                            type: 'POST',
                            data: {
                                _token: $('meta[name="csrf-token"]').attr('content'),
                                _method: 'DELETE'
                            },
                            success: function (response) {
                                activeDataTable.row(button.closest('tr')).remove().draw(false);
                                Swal.fire('Berhasil Dihapus!', response.success, 'success');
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

            $('#category_type').on('change', function () {
                const categoryType = $(this).val();
                const selectedText = $(this).find('option:selected').text();
                let templateUrl = `/templates/import-${categoryType}-template.xlsx`;
                $('#downloadTemplate').attr('href', templateUrl).text(`Download template untuk ${selectedText}`);
            }).trigger('change');
        });
    </script>
@endpush