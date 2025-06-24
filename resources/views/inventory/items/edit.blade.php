@extends('layouts.app')

@section('title', 'Edit Barang')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0 fw-bold text-gradient">
                <i class="fas fa-edit me-2"></i> Edit Barang [{{ $item->name }}]
            </h4>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <h5 class="alert-heading"><i class="fas fa-exclamation-triangle me-2"></i> Terjadi Kesalahan</h5>
                <p>Mohon periksa kembali isian Anda. Ada beberapa data yang tidak valid.</p>
                <hr>
                <ul class="mb-0 ps-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <form action="{{ route('inventory.items.update', $item->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row g-4">
                {{-- KOLOM KIRI: FORM UTAMA --}}
                <div class="col-lg-8">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-header bg-white p-3">
                            <h5 class="mb-0 fw-semibold"><i class="fas fa-file-alt me-2 text-primary"></i>Detail Barang</h5>
                        </div>
                        <div class="card-body">
                            {{-- Kategori (Kontrol Utama) --}}
                            <div class="mb-4">
                                <label for="category_id" class="form-label required">Kategori</label>
                                <select class="form-select @error('category_id') is-invalid @enderror" id="category_id"
                                    name="category_id" required>
                                    <option value="">Pilih Kategori...</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}" data-type="{{ strtolower($category->type) }}" {{ old('category_id', $item->category_id) == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            {{-- WADAH UNTUK FORM DINAMIS --}}
                            <div id="dynamic-fields-container">

                                {{-- Formulir Barang Umum / Lainnya --}}
                                <div id="form-general" class="dynamic-form" style="display: none;">
                                    <div class="row g-3">
                                        <div class="col-12">
                                            <div class="form-floating"><input type="text" class="form-control"
                                                    id="general_name" name="name" value="{{ old('name', $item->name) }}"
                                                    placeholder="Nama Produk"><label for="general_name"
                                                    class="required">Nama Produk</label></div>
                                        </div>
                                        {{-- PERBAIKAN: Menambahkan Harga Modal dan Jual --}}
                                        <div class="col-md-6">
                                            <div class="form-floating"><input type="number" class="form-control"
                                                    id="general_purchase_price" name="purchase_price"
                                                    value="{{ old('purchase_price', $item->purchase_price) }}" min="0"
                                                    placeholder="Harga Modal"><label for="general_purchase_price"
                                                    class="required">Harga Modal</label></div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-floating"><input type="number" class="form-control"
                                                    id="general_price" name="price" value="{{ old('price', $item->price) }}"
                                                    min="0" placeholder="Harga Jual"><label for="general_price"
                                                    class="required">Harga Jual</label></div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-floating"><input type="text" class="form-control"
                                                    id="general_unit" name="unit" value="{{ old('unit', $item->unit) }}"
                                                    placeholder="Contoh: Pcs, Box"><label for="general_unit"
                                                    class="required">Satuan</label></div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-floating"><input type="number" class="form-control"
                                                    id="general_stock" name="stock" value="{{ old('stock', $item->stock) }}"
                                                    min="0" placeholder="Stok"><label for="general_stock"
                                                    class="required">Stok</label></div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-floating"><textarea class="form-control"
                                                    id="general_description" name="description" style="height: 100px"
                                                    placeholder="Deskripsi">{{ old('description', $item->description) }}</textarea><label
                                                    for="general_description">Deskripsi</label></div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Formulir Spesifik untuk Cat --}}
                                <div id="form-cat" class="dynamic-form" style="display: none;">
                                    <div class="row g-3">
                                        <div class="col-12">
                                            <div class="form-floating"><input type="text" class="form-control" id="cat_name"
                                                    name="name" value="{{ old('name', $item->name) }}"
                                                    placeholder="Nama Produk"><label for="cat_name" class="required">Nama
                                                    Produk</label></div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-floating"><input type="text" class="form-control"
                                                    id="paint_type" name="paint_type"
                                                    value="{{ old('paint_type', $item->paint_type) }}"
                                                    placeholder="Cth: Cat Tembok"><label for="paint_type"
                                                    class="required">Jenis Cat</label></div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-floating"><input type="text" class="form-control"
                                                    id="color_name" name="color_name"
                                                    value="{{ old('color_name', $item->color_name) }}"
                                                    placeholder="Cth: Putih Salju"><label for="color_name"
                                                    class="required">Nama Warna</label></div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-floating"><input type="text" class="form-control"
                                                    id="color_code" name="color_code"
                                                    value="{{ old('color_code', $item->color_code) }}"
                                                    placeholder="Cth: #FFFFFF"><label for="color_code">Kode Warna</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-floating"><input type="text" class="form-control" id="volume"
                                                    name="volume" value="{{ old('volume', $item->volume) }}"
                                                    placeholder="Cth: 1 Liter, 5 Kg"><label for="volume"
                                                    class="required">Volume</label></div>
                                        </div>
                                        {{-- PERBAIKAN: Menambahkan Harga Modal dan Jual --}}
                                        <div class="col-md-6">
                                            <div class="form-floating"><input type="number" class="form-control"
                                                    id="cat_purchase_price" name="purchase_price"
                                                    value="{{ old('purchase_price', $item->purchase_price) }}" min="0"
                                                    placeholder="Harga Modal"><label for="cat_purchase_price"
                                                    class="required">Harga Modal</label></div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-floating"><input type="number" class="form-control"
                                                    id="cat_price" name="price" value="{{ old('price', $item->price) }}"
                                                    min="0" placeholder="Harga Jual"><label for="cat_price"
                                                    class="required">Harga Jual</label></div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-floating"><input type="number" class="form-control"
                                                    id="cat_stock" name="stock" value="{{ old('stock', $item->stock) }}"
                                                    min="0" placeholder="Stok"><label for="cat_stock"
                                                    class="required">Stok</label></div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-floating"><textarea class="form-control" id="cat_description"
                                                    name="description" style="height: 100px"
                                                    placeholder="Deskripsi">{{ old('description', $item->description) }}</textarea><label
                                                    for="cat_description">Deskripsi</label></div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Formulir Spesifik untuk Keramik --}}
                                <div id="form-keramik" class="dynamic-form" style="display: none;">
                                    <div class="row g-3">
                                        <div class="col-12">
                                            <div class="form-floating"><input type="text" class="form-control"
                                                    id="keramik_name" name="name" value="{{ old('name', $item->name) }}"
                                                    placeholder="Nama Produk"><label for="keramik_name"
                                                    class="required">Nama Produk</label></div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-floating"><input type="text" class="form-control" id="size"
                                                    name="size" value="{{ old('size', $item->size) }}"
                                                    placeholder="Cth: 60x60"><label for="size" class="required">Ukuran
                                                    (cm)</label></div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-floating"><input type="text" class="form-control"
                                                    id="keramik_unit" name="unit" value="{{ old('unit', $item->unit) }}"
                                                    placeholder="Cth: Box, Dus"><label for="keramik_unit"
                                                    class="required">Satuan</label></div>
                                        </div>
                                        {{-- PERBAIKAN: Layout harga disamakan --}}
                                        <div class="col-md-6">
                                            <div class="form-floating"><input type="number" class="form-control"
                                                    id="purchase_price" name="purchase_price"
                                                    value="{{ old('purchase_price', $item->purchase_price) }}" min="0"
                                                    placeholder="Harga Modal"><label for="purchase_price"
                                                    class="required">Harga Modal</label></div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-floating"><input type="number" class="form-control"
                                                    id="keramik_price" name="price" value="{{ old('price', $item->price) }}"
                                                    min="0" placeholder="Harga Jual"><label for="keramik_price"
                                                    class="required">Harga Jual</label></div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-floating"><input type="number" class="form-control"
                                                    id="keramik_stock" name="stock" value="{{ old('stock', $item->stock) }}"
                                                    min="0" placeholder="Stok"><label for="keramik_stock"
                                                    class="required">Stok</label></div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-floating"><textarea class="form-control"
                                                    id="keramik_description" name="description" style="height: 100px"
                                                    placeholder="Deskripsi">{{ old('description', $item->description) }}</textarea><label
                                                    for="keramik_description">Deskripsi</label></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- KOLOM KANAN: AKSI --}}
                <div class="col-lg-4">
                    <div class="card shadow-sm border-0 sticky-top" style="top: 20px;">
                        <div class="card-header bg-white p-3">
                            <h5 class="mb-0 fw-semibold"><i class="fas fa-cog me-2 text-primary"></i>Aksi</h5>
                        </div>
                        <div class="card-body">
                            <p class="text-muted">Pastikan semua data yang ditandai dengan bintang (*) telah terisi sebelum
                                menyimpan perubahan.</p>
                        </div>
                        <div class="card-footer bg-white p-3">
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-success"><i class="fas fa-save me-2"></i>Simpan
                                    Perubahan</button>
                                <a href="{{ route('inventory.items.index') }}" class="btn btn-outline-secondary">Batal</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
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

        .form-label.required::after {
            content: " *";
            color: var(--bs-danger);
        }

        .dynamic-form {
            animation: fadeIn 0.5s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        // Script JS tidak perlu diubah, karena sudah dinamis.
        // Script dari file create.blade.php bisa digunakan di sini juga.
        document.addEventListener('DOMContentLoaded', function () {
            const categorySelect = document.getElementById('category_id');
            const dynamicForms = document.querySelectorAll('.dynamic-form');

            function updateFormVisibility() {
                const selectedOption = categorySelect.options[categorySelect.selectedIndex];
                const selectedType = selectedOption ? selectedOption.dataset.type : null;

                let formToShowId = null;
                if (selectedType === 'cat') {
                    formToShowId = 'form-cat';
                } else if (selectedType === 'keramik') {
                    formToShowId = 'form-keramik';
                } else if (selectedType) { // Untuk 'general', 'luar', dll.
                    formToShowId = 'form-general';
                }

                dynamicForms.forEach(form => {
                    const formId = form.getAttribute('id');
                    const inputs = form.querySelectorAll('input, textarea, select');
                    const isTarget = (formId === formToShowId);

                    form.style.display = isTarget ? 'block' : 'none';

                    inputs.forEach(input => {
                        if (input.name) {
                            input.disabled = !isTarget;
                        }
                    });
                });
            }

            // Panggil fungsi saat halaman dimuat untuk mengatur state awal
            updateFormVisibility();

            // Tambahkan event listener ke dropdown kategori
            categorySelect.addEventListener('change', updateFormVisibility);
        });
    </script>
@endpush