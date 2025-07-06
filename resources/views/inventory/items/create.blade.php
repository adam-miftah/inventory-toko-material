@extends('layouts.app')

@section('title', 'Tambah Barang Baru')

@section('content')
  <div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0 fw-bold text-gradient">
      <i class="fas fa-plus-circle me-2"></i> Tambah Barang Baru
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

    <form action="{{ route('inventory.items.store') }}" method="POST">
    @csrf
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
          <select class="form-select @error('category_id') is-invalid @enderror" id="category_id" name="category_id"
          required>
          <option value="">Pilih Kategori...</option>
          @foreach ($categories as $category)
        <option value="{{ $category->id }}" data-type="{{ strtolower($category->type) }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
        {{ $category->name }}
        </option>
      @endforeach
          </select>
          @error('category_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        {{-- WADAH UNTUK FORM DINAMIS --}}
        <div id="dynamic-fields-container">

          {{-- Formulir Barang Umum / Luar --}}
          <div id="form-general" class="dynamic-form" style="display: none;">
          <div class="row g-3">
            <div class="col-md-12"><label class="form-label required">Nama Produk</label><input type="text"
              class="form-control" name="name" value="{{ old('name') }}"></div>
            {{-- PERBAIKAN: Menambahkan Harga Modal dan Jual --}}
            <div class="col-md-6"><label class="form-label required">Harga Modal</label><input type="number"
              class="form-control" name="purchase_price" value="{{ old('purchase_price') }}" min="0"></div>
            <div class="col-md-6"><label class="form-label required">Harga Jual</label><input type="number"
              class="form-control" name="price" value="{{ old('price') }}" min="0"></div>
            <div class="col-md-6"><label class="form-label required">Satuan</label><input type="text"
              class="form-control" name="unit" value="{{ old('unit') }}" placeholder="Contoh: Pcs, Box, Pack">
            </div>
            <div class="col-md-6"><label class="form-label required">Stok</label><input type="number"
              class="form-control" name="stock" value="{{ old('stock') }}" min="0"></div>
            <div class="col-12"><label class="form-label">Deskripsi</label><textarea class="form-control"
              name="description" rows="2">{{ old('description') }}</textarea></div>
          </div>
          </div>

          {{-- Formulir Spesifik untuk Cat --}}
          <div id="form-cat" class="dynamic-form" style="display: none;">
          <div class="row g-3">
            <div class="col-md-12"><label class="form-label required">Nama Produk</label><input type="text"
              class="form-control" name="name" value="{{ old('name') }}"></div>
            <div class="col-md-6"><label class="form-label required">Jenis Cat</label><input type="text"
              class="form-control" name="paint_type" value="{{ old('paint_type') }}"
              placeholder="Cth: Cat Tembok"></div>
            <div class="col-md-6"><label class="form-label required">Nama Warna</label><input type="text"
              class="form-control" name="color_name" value="{{ old('color_name') }}"
              placeholder="Cth: Putih Salju"></div>
            <div class="col-md-6"><label class="form-label">Kode Warna</label><input type="text"
              class="form-control" name="color_code" value="{{ old('color_code') }}" placeholder="Cth: #FFFFFF">
            </div>
            <div class="col-md-6"><label class="form-label required">Volume</label><input type="text"
              class="form-control" name="volume" value="{{ old('volume') }}" placeholder="Cth: 1 Liter, 5 Kg">
            </div>
            {{-- PERBAIKAN: Menambahkan Harga Modal dan Jual --}}
            <div class="col-md-6"><label class="form-label required">Harga Modal</label><input type="number"
              class="form-control" name="purchase_price" value="{{ old('purchase_price') }}" min="0"></div>
            <div class="col-md-6"><label class="form-label required">Harga Jual</label><input type="number"
              class="form-control" name="price" value="{{ old('price') }}" min="0"></div>
            <div class="col-md-12"><label class="form-label required">Stok</label><input type="number"
              class="form-control" name="stock" value="{{ old('stock') }}" min="0"></div>
            <div class="col-12"><label class="form-label">Deskripsi</label><textarea class="form-control"
              name="description" rows="2">{{ old('description') }}</textarea></div>
          </div>
          </div>

          {{-- Formulir Spesifik untuk Keramik --}}
          <div id="form-keramik" class="dynamic-form" style="display: none;">
          <div class="row g-3">
            <div class="col-md-12"><label class="form-label required">Nama Produk</label><input type="text"
              class="form-control" name="name" value="{{ old('name') }}"></div>
            <div class="col-md-6"><label class="form-label required">Ukuran (cm)</label><input type="text"
              class="form-control" name="size" value="{{ old('size') }}" placeholder="Cth: 60x60"></div>
            <div class="col-md-6"><label class="form-label required">Satuan</label><input type="text"
              class="form-control" name="unit" value="{{ old('unit') }}" placeholder="Cth: Box, Dus"></div>
            {{-- PERBAIKAN: Layout harga disamakan --}}
            <div class="col-md-6"><label class="form-label required">Harga Modal</label><input type="number"
              class="form-control" name="purchase_price" value="{{ old('purchase_price') }}" min="0"></div>
            <div class="col-md-6"><label class="form-label required">Harga Jual</label><input type="number"
              class="form-control" name="price" value="{{ old('price') }}" min="0"></div>
            <div class="col-md-12"><label class="form-label required">Stok</label><input type="number"
              class="form-control" name="stock" value="{{ old('stock') }}" min="0"></div>
            <div class="col-12"><label class="form-label">Deskripsi</label><textarea class="form-control"
              name="description" rows="2">{{ old('description') }}</textarea></div>
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
        <p class="text-muted">Pastikan semua data yang ditandai dengan bintang (*) telah terisi sebelum menyimpan.
        </p>
        </div>
        <div class="card-footer bg-white p-3">
        <div class="d-grid gap-2">
          <button type="submit" class="btn btn-primary"><i class="fas fa-save me-2"></i>Simpan Barang</button>
          <a href="{{ route('inventory.items.index') }}" class="btn btn-secondary">Batal</a>
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
    document.addEventListener('DOMContentLoaded', function () {
    const categorySelect = document.getElementById('category_id');
    const dynamicForms = document.querySelectorAll('.dynamic-form');
    const container = document.getElementById('dynamic-fields-container');

    function updateFormVisibility() {
      const selectedOption = categorySelect.options[categorySelect.selectedIndex];
      const selectedType = selectedOption ? selectedOption.dataset.type : null;

      let formToShowId = null;
      if (selectedType === 'cat') {
      formToShowId = 'form-cat';
      } else if (selectedType === 'keramik') {
      formToShowId = 'form-keramik';
      } else if (selectedType) { // Untuk 'general', 'luar', atau tipe lain
      formToShowId = 'form-general';
      }

      // Sembunyikan semua form dan disable semua inputnya
      dynamicForms.forEach(form => {
      const formId = form.getAttribute('id');
      const isTarget = (formId === formToShowId);

      form.style.display = isTarget ? 'block' : 'none';

      form.querySelectorAll('input, textarea, select').forEach(input => {
        // Hanya disable input yang punya 'name' untuk mencegah disable pada elemen tak terduga
        if (input.name) {
        input.disabled = !isTarget;
        }
      });
      });
    }

    // Panggil fungsi saat halaman dimuat (untuk handle old input)
    updateFormVisibility();

    // Tambahkan event listener ke dropdown kategori
    categorySelect.addEventListener('change', updateFormVisibility);
    });
  </script>
@endpush