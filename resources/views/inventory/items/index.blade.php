@extends('layouts.app')

@section('title', 'Daftar Barang')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0">
                <i class="fas fa-boxes text-primary"></i> Daftar Barang
            </h4>
            <div>
                <a href="{{ route('inventory.items.create') }}" class="btn btn-outline-primary btn-sm">
                    <i class="fas fa-plus-circle"></i> Tambah Barang Baru
                </a>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-3">
                <div class="row g-3 align-items-center">
                    <div class="col-md-6">
                        <div class="input-group">
                            <span class="input-group-text bg-transparent border-end-0">
                                <i class="fas fa-search text-muted"></i>
                            </span>
                            <input type="text" class="form-control border-start-0" id="search_input"
                                placeholder="Cari barang...">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-center justify-content-md-end">
                            <label for="category_filter" class="form-label mb-0 me-2 d-none d-md-block">
                                <i class="fas fa-filter text-muted"></i> Filter:
                            </label>
                            <select class="form-select" id="category_filter" style="max-width: 250px;">
                                <option value="general" {{ Request::get('category_id') == 'general' || !Request::get('category_id') ? 'selected' : '' }}>
                                    <i class="fas fa-cubes"></i> Umum
                                </option>
                                @foreach ($categories as $category)
                                    <option value="{{ strtolower($category->name) }}" {{ Request::get('category_id') == strtolower($category->name) ? 'selected' : '' }}>
                                        @if(strtolower($category->name) == 'cat')
                                            <i class="fas fa-paint-roller"></i>
                                        @elseif(strtolower($category->name) == 'keramik')
                                            <i class="fas fa-border-style"></i>
                                        @else
                                            <i class="fas fa-box"></i>
                                        @endif
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tabel untuk Kategori Umum (Default) --}}
        <div id="general-items-table-container" class="card shadow-sm mb-4 p-3">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="fas fa-cubes me-2"></i>Daftar Barang Umum
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0" id="general-items-table">
                        <thead class="table-light">
                            <tr>
                                <th width="5%">ID</th>
                                <th>Nama Produk</th>
                                <th>Kategori</th>
                                <th class="text-end">Harga</th>
                                <th class="text-center">Stok</th>
                                <th>Satuan</th>
                                <th width="15%" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($generalItems as $item)
                                <tr>
                                    <td class="fw-semibold">#{{ $item->id }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0 me-2">
                                                <div class="bg-light rounded p-1 text-center"
                                                    style="width: 30px; height: 30px;">
                                                    <i class="fas fa-box text-muted"></i>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1">
                                                {{ $item->name }}
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-info bg-opacity-10 text-info">
                                            {{ $item->category->name ?? 'N/A' }}
                                        </span>
                                    </td>
                                    <td class="text-end fw-semibold">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                    <td class="text-center">
                                        <span
                                            class="badge bg-{{ $item->stock > 0 ? 'success' : 'danger' }}-subtle text-{{ $item->stock > 0 ? 'success' : 'danger' }}">
                                            {{ $item->stock }}
                                        </span>
                                    </td>
                                    <td>{{ $item->unit ?? '-' }}</td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-2">
                                            <a href="{{ route('inventory.items.show', $item->id) }}"
                                                class="btn btn-sm btn-outline-info rounded-circle" data-bs-toggle="tooltip"
                                                title="Detail">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('inventory.items.edit', $item->id) }}"
                                                class="btn btn-sm btn-outline-warning rounded-circle" data-bs-toggle="tooltip"
                                                title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('inventory.items.destroy', $item->id) }}" method="POST"
                                                onsubmit="return confirm('Anda yakin ingin menghapus barang ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger rounded-circle"
                                                    data-bs-toggle="tooltip" title="Hapus">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4 text-muted">
                                        <i class="fas fa-box-open fa-2x mb-3"></i>
                                        <p>Tidak ada data barang</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Tabel untuk Kategori Cat --}}
        <div id="cat-items-table-container" class="card shadow-sm mb-4 p-3" style="display: none;">
            <div class="card-header bg-warning text-dark">
                <h5 class="mb-0">
                    <i class="fas fa-paint-roller me-2"></i>Daftar Barang Cat
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0" id="cat-items-table">
                        <thead class="table-light">
                            <tr>
                                <th width="5%">ID</th>
                                <th>Nama Produk</th>
                                <th>Jenis</th>
                                <th>Warna</th>
                                <th>Kode</th>
                                <th>Satuan</th>
                                <th class="text-end">Harga</th>
                                <th class="text-center">Stok</th>
                                <th width="15%" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($catItems as $item)
                                <tr>
                                    <td class="fw-semibold">#{{ $item->id }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0 me-2">
                                                <div class="bg-light rounded p-1 text-center"
                                                    style="width: 30px; height: 30px;">
                                                    <i class="fas fa-paint-roller text-warning"></i>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1">
                                                {{ $item->name }}
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $item->paint_type ?? '-' }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($item->color_code)
                                                <div class="flex-shrink-0 me-2">
                                                    <div
                                                        style="width: 16px; height: 16px; background-color: {{ $item->color_code }}; border: 1px solid #ddd;">
                                                    </div>
                                                </div>
                                            @endif
                                            <div class="flex-grow-1">
                                                {{ $item->color_name ?? '-' }}
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if($item->color_code)
                                            <span class="badge bg-light text-dark">{{ $item->color_code }}</span>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>{{ $item->volume ?? '-' }}</td>
                                    <td class="text-end fw-semibold">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                    <td class="text-center">
                                        <span
                                            class="badge bg-{{ $item->stock > 0 ? 'success' : 'danger' }}-subtle text-{{ $item->stock > 0 ? 'success' : 'danger' }}">
                                            {{ $item->stock }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-2">
                                            <a href="{{ route('inventory.items.show', $item->id) }}"
                                                class="btn btn-sm btn-outline-info rounded-circle" data-bs-toggle="tooltip"
                                                title="Detail">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('inventory.items.edit', $item->id) }}"
                                                class="btn btn-sm btn-outline-warning rounded-circle" data-bs-toggle="tooltip"
                                                title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('inventory.items.destroy', $item->id) }}" method="POST"
                                                onsubmit="return confirm('Anda yakin ingin menghapus barang ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger rounded-circle"
                                                    data-bs-toggle="tooltip" title="Hapus">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center py-4 text-muted">
                                        <i class="fas fa-paint-roller fa-2x mb-3"></i>
                                        <p>Tidak ada data barang cat</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Tabel untuk Kategori Keramik --}}
        <div id="keramik-items-table-container" class="card shadow-sm mb-4 p-3" style="display: none;">
            <div class="card-header bg-danger text-white">
                <h5 class="mb-0">
                    <i class="fas fa-border-style me-2"></i>Daftar Barang Keramik
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0" id="keramik-items-table">
                        <thead class="table-light">
                            <tr>
                                <th width="5%">ID</th>
                                <th>Nama Produk</th>
                                <th>Ukuran</th>
                                <th class="text-end">Modal</th>
                                <th class="text-end">Jual</th>
                                <th>Satuan</th>
                                <th class="text-center">Stok</th>
                                <th width="15%" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($keramikItems as $item)
                                <tr>
                                    <td class="fw-semibold">#{{ $item->id }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0 me-2">
                                                <div class="bg-light rounded p-1 text-center"
                                                    style="width: 30px; height: 30px;">
                                                    <i class="fas fa-border-style text-danger"></i>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1">
                                                {{ $item->name }}
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $item->size ?? '-' }}</td>
                                    <td class="text-end">Rp {{ number_format($item->purchase_price, 0, ',', '.') }}</td>
                                    <td class="text-end fw-semibold">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                    <td>{{ $item->unit ?? '-' }}</td>
                                    <td class="text-center">
                                        <span
                                            class="badge bg-{{ $item->stock > 0 ? 'success' : 'danger' }}-subtle text-{{ $item->stock > 0 ? 'success' : 'danger' }}">
                                            {{ $item->stock }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-2">
                                            <a href="{{ route('inventory.items.show', $item->id) }}"
                                                class="btn btn-sm btn-outline-info rounded-circle" data-bs-toggle="tooltip"
                                                title="Detail">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('inventory.items.edit', $item->id) }}"
                                                class="btn btn-sm btn-outline-warning rounded-circle" data-bs-toggle="tooltip"
                                                title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('inventory.items.destroy', $item->id) }}" method="POST"
                                                onsubmit="return confirm('Anda yakin ingin menghapus barang ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger rounded-circle"
                                                    data-bs-toggle="tooltip" title="Hapus">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4 text-muted">
                                        <i class="fas fa-border-style fa-2x mb-3"></i>
                                        <p>Tidak ada data barang keramik</p>
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
        .card-header {
            border-bottom: none;
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

        .empty-state {
            padding: 3rem 0;
            text-align: center;
        }

        .empty-state i {
            font-size: 3rem;
            margin-bottom: 1rem;
            color: #dee2e6;
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
    <script>
        $(document).ready(function () {
            // Enable tooltips
            $('[data-bs-toggle="tooltip"]').tooltip();

            // Inisialisasi DataTables untuk setiap tabel dengan definisi kolom yang tepat

            // 1. Tabel General (7 kolom)
            const generalTable = $('#general-items-table').DataTable({
                "columns": [
                    null, // ID
                    null, // Nama Produk
                    null, // Kategori
                    null, // Harga
                    null, // Stok
                    null, // Satuan
                    { "orderable": false, "searchable": false } // Aksi
                ],
                "paging": true,
                "lengthChange": false,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
                "language": {
                    "search": "Cari:",
                    "zeroRecords": "Tidak ditemukan data yang cocok",
                    "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                    "infoEmpty": "Menampilkan 0 sampai 0 dari 0 data",
                    "paginate": {
                        "first": "Pertama",
                        "last": "Terakhir",
                        "next": "Selanjutnya",
                        "previous": "Sebelumnya"
                    }
                },
                "responsivePriority": 1
            });

            // 2. Tabel Cat (9 kolom)
            const catTable = $('#cat-items-table').DataTable({
                "columns": [
                    null, // ID
                    null, // Nama Produk
                    null, // Jenis
                    null, // Warna
                    null, // Kode
                    null, // Satuan
                    null, // Harga
                    null, // Stok
                    { "orderable": false, "searchable": false } // Aksi
                ],
                "paging": true,
                "lengthChange": false,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
                "language": {
                    "search": "Cari:",
                    "zeroRecords": "Tidak ditemukan data yang cocok",
                    "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                    "infoEmpty": "Menampilkan 0 sampai 0 dari 0 data",
                    "paginate": {
                        "first": "Pertama",
                        "last": "Terakhir",
                        "next": "Selanjutnya",
                        "previous": "Sebelumnya"
                    }
                },
                "responsivePriority": 1
            });

            // 3. Tabel Keramik (8 kolom)
            const keramikTable = $('#keramik-items-table').DataTable({
                "columns": [
                    null, // ID
                    null, // Nama Produk
                    null, // Ukuran
                    null, // Modal
                    null, // Jual
                    null, // Satuan
                    null, // Stok
                    { "orderable": false, "searchable": false } // Aksi
                ],
                "paging": true,
                "lengthChange": false,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
                "language": {
                    "search": "Cari:",
                    "zeroRecords": "Tidak ditemukan data yang cocok",
                    "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                    "infoEmpty": "Menampilkan 0 sampai 0 dari 0 data",
                    "paginate": {
                        "first": "Pertama",
                        "last": "Terakhir",
                        "next": "Selanjutnya",
                        "previous": "Sebelumnya"
                    }
                },
                "responsivePriority": 1
            });

            // Elemen DOM
            const categoryFilter = $('#category_filter');
            const searchInput = $('#search_input');
            const generalTableContainer = $('#general-items-table-container');
            const catTableContainer = $('#cat-items-table-container');
            const keramikTableContainer = $('#keramik-items-table-container');

            // Fungsi untuk menampilkan tabel berdasarkan kategori
            function showSelectedCategoryTable() {
                const selectedCategory = categoryFilter.val();

                // Sembunyikan semua tabel
                generalTableContainer.hide();
                catTableContainer.hide();
                keramikTableContainer.hide();

                // Tampilkan tabel yang dipilih
                if (selectedCategory === 'general') {
                    generalTableContainer.show();
                } else if (selectedCategory === 'cat') {
                    catTableContainer.show();
                } else if (selectedCategory === 'keramik') {
                    keramikTableContainer.show();
                } else {
                    generalTableContainer.show();
                }
            }

            // Fungsi untuk menerapkan pencarian ke tabel aktif
            function applySearchToActiveTable(searchTerm) {
                const selectedCategory = categoryFilter.val();

                if (selectedCategory === 'general') {
                    generalTable.search(searchTerm).draw();
                } else if (selectedCategory === 'cat') {
                    catTable.search(searchTerm).draw();
                } else if (selectedCategory === 'keramik') {
                    keramikTable.search(searchTerm).draw();
                } else {
                    generalTable.search(searchTerm).draw();
                }
            }

            // Event listener untuk filter kategori
            categoryFilter.on('change', function () {
                showSelectedCategoryTable();
                // Reset pencarian saat ganti kategori
                searchInput.val('');
                applySearchToActiveTable('');
            });

            // Event listener untuk input pencarian
            searchInput.on('keyup', function () {
                const searchTerm = this.value;
                applySearchToActiveTable(searchTerm);
            });

            // Responsive layout untuk mobile
            function setupResponsiveTables() {
                if ($(window).width() < 768) {
                    // Setup untuk tabel general
                    $('#general-items-table').find('tbody tr').each(function () {
                        $(this).find('td').each(function (index) {
                            const headerText = $('#general-items-table thead th').eq(index).text();
                            $(this).attr('data-label', headerText);
                        });
                    });

                    // Setup untuk tabel cat
                    $('#cat-items-table').find('tbody tr').each(function () {
                        $(this).find('td').each(function (index) {
                            const headerText = $('#cat-items-table thead th').eq(index).text();
                            $(this).attr('data-label', headerText);
                        });
                    });

                    // Setup untuk tabel keramik
                    $('#keramik-items-table').find('tbody tr').each(function () {
                        $(this).find('td').each(function (index) {
                            const headerText = $('#keramik-items-table thead th').eq(index).text();
                            $(this).attr('data-label', headerText);
                        });
                    });
                }
            }

            // Panggil fungsi saat pertama kali load
            showSelectedCategoryTable();
            setupResponsiveTables();

            // Handle responsive layout on window resize
            $(window).resize(function () {
                setupResponsiveTables();
            });

            // Jika ada pesan sukses dari session
            @if(session('category_id_filter'))
                const savedFilter = "{{ session('category_id_filter') }}";
                if (savedFilter) {
                    categoryFilter.val(savedFilter).trigger('change');
                }
            @endif
    });                                                                                                                                                    });
    </script>
@endpush