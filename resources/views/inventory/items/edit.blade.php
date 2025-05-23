@extends('layouts.app')

@section('title', 'Edit Barang')

@php
    // Definisikan daftar bidang di PHP
    $generalInputNames = ['name', 'price', 'unit', 'stock', 'description'];
    $catInputNames = ['name', 'paint_type', 'color_name', 'color_code', 'volume', 'price', 'stock', 'description'];
    $keramikInputNames = ['name', 'size', 'purchase_price', 'price', 'unit', 'stock', 'description'];
    // Gabungkan semua nama bidang untuk iterasi old()
    $allInputNames = array_unique(array_merge($generalInputNames, $catInputNames, $keramikInputNames));
@endphp

@section('content')
    <div class="container-fluid">
        <div class=" d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0">Edit Barang</h4>
            <a href="{{ route('inventory.items') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-arrow-left me-2"></i>Kembali
            </a>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4">
                        <form action="{{ route('inventory.items.update', $item->id) }}" method="POST"
                            class="needs-validation" novalidate>
                            @csrf
                            @method('PUT')

                            <div class="row g-4">
                                <div class="col-md-12">
                                    <div class="form-floating">
                                        <select class="form-select @error('category_id') is-invalid @enderror"
                                            id="category_id" name="category_id" required>
                                            <option value="">Pilih Kategori</option>
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}" {{ (old('category_id', $item->category_id) == $category->id) ? 'selected' : '' }}>
                                                    {{ $category->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <label for="category_id">Kategori <span class="text-danger">*</span></label>
                                        @error('category_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Formulir Umum --}}
                                <div id="general-fields" style="display: none;">
                                    <div class="col-12">
                                        <div class="bg-light p-3 rounded mb-4">
                                            <h6 class="mb-3 text-primary">
                                                <i class="fas fa-box-open me-2"></i>Detail Barang Umum
                                            </h6>
                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <div class="form-floating">
                                                        <input type="text"
                                                            class="form-control @error('name') is-invalid @enderror"
                                                            id="general_name" name="name"
                                                            value="{{ old('name', $item->name) }}"
                                                            placeholder="Nama Produk">
                                                        <label for="general_name">Nama Produk <span
                                                                class="text-danger">*</span></label>
                                                        @error('name')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-floating">
                                                        <input type="number"
                                                            class="form-control @error('price') is-invalid @enderror"
                                                            id="general_price" name="price"
                                                            value="{{ old('price', $item->price) }}" min="0"
                                                            placeholder="Harga">
                                                        <label for="general_price">Harga <span
                                                                class="text-danger">*</span></label>
                                                        @error('price')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-floating">
                                                        <input type="text"
                                                            class="form-control @error('unit') is-invalid @enderror"
                                                            id="general_unit" name="unit"
                                                            value="{{ old('unit', $item->unit) }}" placeholder="Satuan">
                                                        <label for="general_unit">Satuan <span
                                                                class="text-danger">*</span></label>
                                                        @error('unit')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-floating">
                                                        <input type="number"
                                                            class="form-control @error('stock') is-invalid @enderror"
                                                            id="general_stock" name="stock"
                                                            value="{{ old('stock', $item->stock) }}" min="0"
                                                            placeholder="Stok">
                                                        <label for="general_stock">Stok <span
                                                                class="text-danger">*</span></label>
                                                        @error('stock')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="form-floating">
                                                        <textarea
                                                            class="form-control @error('description') is-invalid @enderror"
                                                            id="general_description" name="description"
                                                            style="height: 100px"
                                                            placeholder="Deskripsi">{{ old('description', $item->description) }}</textarea>
                                                        <label for="general_description">Deskripsi (Opsional)</label>
                                                        @error('description')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Formulir Spesifik untuk Cat --}}
                                <div id="cat-fields" style="display: none;">
                                    <div class="col-12">
                                        <div class="bg-light p-3 rounded mb-4">
                                            <h6 class="mb-3 text-primary">
                                                <i class="fas fa-paint-roller me-2"></i>Detail Barang Cat
                                            </h6>
                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <div class="form-floating">
                                                        <input type="text"
                                                            class="form-control @error('name') is-invalid @enderror"
                                                            id="cat_name" name="name" value="{{ old('name', $item->name) }}"
                                                            placeholder="Nama Produk">
                                                        <label for="cat_name">Nama Produk <span
                                                                class="text-danger">*</span></label>
                                                        @error('name')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-floating">
                                                        <input type="text"
                                                            class="form-control @error('paint_type') is-invalid @enderror"
                                                            id="paint_type" name="paint_type"
                                                            value="{{ old('paint_type', $item->paint_type) }}"
                                                            placeholder="Jenis Cat">
                                                        <label for="paint_type">Jenis Cat <span
                                                                class="text-danger">*</span></label>
                                                        @error('paint_type')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-floating">
                                                        <input type="text"
                                                            class="form-control @error('color_name') is-invalid @enderror"
                                                            id="color_name" name="color_name"
                                                            value="{{ old('color_name', $item->color_name) }}"
                                                            placeholder="Nama Warna">
                                                        <label for="color_name">Nama Warna <span
                                                                class="text-danger">*</span></label>
                                                        @error('color_name')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-floating">
                                                        <input type="text"
                                                            class="form-control @error('color_code') is-invalid @enderror"
                                                            id="color_code" name="color_code"
                                                            value="{{ old('color_code', $item->color_code) }}"
                                                            placeholder="Kode Warna">
                                                        <label for="color_code">Kode Warna (Opsional)</label>
                                                        @error('color_code')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-floating">
                                                        <input type="text"
                                                            class="form-control @error('volume') is-invalid @enderror"
                                                            id="volume" name="volume"
                                                            value="{{ old('volume', $item->volume) }}"
                                                            placeholder="Berat/Volume">
                                                        <label for="volume">Berat/Volume (Kg/Liter) <span
                                                                class="text-danger">*</span></label>
                                                        @error('volume')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-floating">
                                                        <input type="number"
                                                            class="form-control @error('price') is-invalid @enderror"
                                                            id="cat_price" name="price"
                                                            value="{{ old('price', $item->price) }}" min="0"
                                                            placeholder="Harga">
                                                        <label for="cat_price">Harga <span
                                                                class="text-danger">*</span></label>
                                                        @error('price')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-floating">
                                                        <input type="number"
                                                            class="form-control @error('stock') is-invalid @enderror"
                                                            id="cat_stock" name="stock"
                                                            value="{{ old('stock', $item->stock) }}" min="0"
                                                            placeholder="Stok">
                                                        <label for="cat_stock">Stok <span
                                                                class="text-danger">*</span></label>
                                                        @error('stock')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="form-floating">
                                                        <textarea
                                                            class="form-control @error('description') is-invalid @enderror"
                                                            id="cat_description" name="description" style="height: 100px"
                                                            placeholder="Deskripsi">{{ old('description', $item->description) }}</textarea>
                                                        <label for="cat_description">Deskripsi (Opsional)</label>
                                                        @error('description')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Formulir Spesifik untuk Keramik --}}
                                <div id="keramik-fields" style="display: none;">
                                    <div class="col-12">
                                        <div class="bg-light p-3 rounded mb-4">
                                            <h6 class="mb-3 text-primary">
                                                <i class="fas fa-border-style me-2"></i>Detail Barang Keramik
                                            </h6>
                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <div class="form-floating">
                                                        <input type="text"
                                                            class="form-control @error('name') is-invalid @enderror"
                                                            id="keramik_name" name="name"
                                                            value="{{ old('name', $item->name) }}"
                                                            placeholder="Nama Produk">
                                                        <label for="keramik_name">Nama Produk <span
                                                                class="text-danger">*</span></label>
                                                        @error('name')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-floating">
                                                        <input type="text"
                                                            class="form-control @error('size') is-invalid @enderror"
                                                            id="size" name="size" value="{{ old('size', $item->size) }}"
                                                            placeholder="Ukuran">
                                                        <label for="size">Ukuran (cm) <span
                                                                class="text-danger">*</span></label>
                                                        @error('size')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-floating">
                                                        <input type="number"
                                                            class="form-control @error('purchase_price') is-invalid @enderror"
                                                            id="purchase_price" name="purchase_price"
                                                            value="{{ old('purchase_price', $item->purchase_price) }}"
                                                            min="0" placeholder="Harga Modal">
                                                        <label for="purchase_price">Harga Modal <span
                                                                class="text-danger">*</span></label>
                                                        @error('purchase_price')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-floating">
                                                        <input type="number"
                                                            class="form-control @error('price') is-invalid @enderror"
                                                            id="keramik_price" name="price"
                                                            value="{{ old('price', $item->price) }}" min="0"
                                                            placeholder="Harga Jual">
                                                        <label for="keramik_price">Harga Jual <span
                                                                class="text-danger">*</span></label>
                                                        @error('price')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-floating">
                                                        <input type="text"
                                                            class="form-control @error('unit') is-invalid @enderror"
                                                            id="keramik_unit" name="unit"
                                                            value="{{ old('unit', $item->unit) }}" placeholder="Satuan">
                                                        <label for="keramik_unit">Satuan <span
                                                                class="text-danger">*</span></label>
                                                        @error('unit')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-floating">
                                                        <input type="number"
                                                            class="form-control @error('stock') is-invalid @enderror"
                                                            id="keramik_stock" name="stock"
                                                            value="{{ old('stock', $item->stock) }}" min="0"
                                                            placeholder="Stok">
                                                        <label for="keramik_stock">Stok <span
                                                                class="text-danger">*</span></label>
                                                        @error('stock')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="form-floating">
                                                        <textarea
                                                            class="form-control @error('description') is-invalid @enderror"
                                                            id="keramik_description" name="description"
                                                            style="height: 100px"
                                                            placeholder="Deskripsi">{{ old('description', $item->description) }}</textarea>
                                                        <label for="keramik_description">Deskripsi (Opsional)</label>
                                                        @error('description')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="d-flex justify-content-end gap-2">
                                        <button type="reset" class="btn btn-outline-secondary btn-sm">
                                            <i class="fas fa-undo me-2"></i>Reset
                                        </button>
                                        <button type="submit" class="btn btn-success btn-sm">
                                            <i class="fas fa-save me-2"></i>Simpan Perubahan
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
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

        .form-floating label {
            font-weight: 500;
        }

        .form-control,
        .form-select {
            border-radius: 8px;
            padding: 12px 16px;
        }

        .form-control:focus,
        .form-select:focus {
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.15);
            border-color: #86b7fe;
        }

        .invalid-feedback {
            font-size: 0.85rem;
        }

        .bg-light {
            background-color: #f8f9fa !important;
        }

        @media (max-width: 768px) {
            .card-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const categorySelect = document.getElementById('category_id');
            const generalFields = document.getElementById('general-fields');
            const keramikFields = document.getElementById('keramik-fields');
            const catFields = document.getElementById('cat-fields');

            // Dapatkan ID kategori "Cat" dan "Keramik" dari data PHP
            const catCategoryId = {{ $catCategoryId ?? 'null' }};
            const keramikCategoryId = {{ $keramikCategoryId ?? 'null' }};

            const generalInputNames = @json($generalInputNames);
            const catInputNames = @json($catInputNames);
            const keramikInputNames = @json($keramikInputNames);
            const allInputNames = @json($allInputNames);

            // Fungsi untuk mengelola atribut 'required' dan 'name' pada input
            function toggleInputAttributes(container, isRequired, inputNames, currentItem = {}) {
                const inputs = container.querySelectorAll('input, select, textarea');
                inputs.forEach(input => {
                    let fieldName = input.name;
                    // Jika nama sudah diubah menjadi 'hidden_fieldname', kembalikan ke nama asli
                    if (fieldName.startsWith('hidden_')) {
                        fieldName = fieldName.substring(7); // Hapus 'hidden_'
                    }

                    if (inputNames.includes(fieldName)) {
                        if (isRequired) {
                            input.name = fieldName;

                            if (['name', 'price', 'unit', 'stock', 'size', 'purchase_price', 'paint_type', 'color_name', 'volume'].includes(fieldName)) {
                                input.setAttribute('required', 'required');
                                const label = input.parentElement.querySelector('label');
                                if (label && !label.querySelector('.text-danger')) {
                                    label.innerHTML += ' <span class="text-danger">*</span>';
                                }
                            }

                            // Mengisi nilai dari old() atau dari $item
                            if (currentItem.hasOwnProperty(fieldName)) {
                                input.value = currentItem[fieldName];
                            } else {
                                input.value = '';
                            }

                        } else {
                            input.removeAttribute('required');
                            input.name = 'hidden_' + fieldName;
                            const label = input.parentElement.querySelector('label');
                            if (label) {
                                const span = label.querySelector('.text-danger');
                                if (span) span.remove();
                            }
                            input.value = '';
                        }
                    }
                    input.classList.remove('is-invalid');
                    const invalidFeedback = input.nextElementSibling;
                    if (invalidFeedback && invalidFeedback.classList.contains('invalid-feedback')) {
                        invalidFeedback.style.display = 'none';
                    }
                });
            }

            function showCategoryFields() {
                const selectedCategoryId = categorySelect.value;

                generalFields.style.display = 'none';
                keramikFields.style.display = 'none';
                catFields.style.display = 'none';

                toggleInputAttributes(generalFields, false, generalInputNames);
                toggleInputAttributes(keramikFields, false, keramikInputNames);
                toggleInputAttributes(catFields, false, catInputNames);

                const currentItemData = {
                    name: {{ Js::from(old('name', $item->name ?? '')) }},
                    price: {{ Js::from(old('price', $item->price ?? '')) }},
                    purchase_price: {{ Js::from(old('purchase_price', $item->purchase_price ?? '')) }},
                    unit: {{ Js::from(old('unit', $item->unit ?? '')) }},
                    stock: {{ Js::from(old('stock', $item->stock ?? '')) }},
                    description: {{ Js::from(old('description', $item->description ?? '')) }},
                    size: {{ Js::from(old('size', $item->size ?? '')) }},
                    texture: {{ Js::from(old('texture', $item->texture ?? '')) }},
                    motif: {{ Js::from(old('motif', $item->motif ?? '')) }},
                    grade: {{ Js::from(old('grade', $item->grade ?? '')) }},
                    color_name: {{ Js::from(old('color_name', $item->color_name ?? '')) }},
                    color_code: {{ Js::from(old('color_code', $item->color_code ?? '')) }},
                    paint_type: {{ Js::from(old('paint_type', $item->paint_type ?? '')) }},
                    finish_type: {{ Js::from(old('finish_type', $item->finish_type ?? '')) }},
                    volume: {{ Js::from(old('volume', $item->volume ?? '')) }},
                };

                if (selectedCategoryId == catCategoryId) {
                    currentFieldsContainer = catFields;
                    currentInputNames = catInputNames;
                    catFields.style.display = 'block';
                } else if (selectedCategoryId == keramikCategoryId) {
                    currentFieldsContainer = keramikFields;
                    currentInputNames = keramikInputNames;
                    keramikFields.style.display = 'block';
                } else if (selectedCategoryId) {
                    currentFieldsContainer = generalFields;
                    currentInputNames = generalInputNames;
                    generalFields.style.display = 'block';
                }

                if (currentFieldsContainer) {
                    toggleInputAttributes(currentFieldsContainer, true, currentInputNames, currentItemData);
                }

                document.querySelectorAll('.form-control.is-invalid').forEach(input => {
                    input.classList.remove('is-invalid');
                });
                document.querySelectorAll('.invalid-feedback').forEach(feedback => {
                    feedback.style.display = 'none';
                });
            }

            showCategoryFields();
            categorySelect.addEventListener('change', showCategoryFields);

            @if($errors->any())
                const oldCategoryId = "{{ old('category_id', $item->category_id) }}";
                if (oldCategoryId) {
                    categorySelect.value = oldCategoryId;
                    showCategoryFields();
                    // Tampilkan kembali pesan error validasi
                    document.querySelectorAll('.form-control.is-invalid').forEach(input => {
                        const feedback = input.nextElementSibling;
                        if (feedback && feedback.classList.contains('invalid-feedback')) {
                            feedback.style.display = 'block';
                        }
                    });
                }
            @endif
                                    });
    </script>
@endpush