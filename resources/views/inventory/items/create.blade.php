@extends('layouts.app')

@section('title', 'Tambah Barang')

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
    <h4 class="mb-4">Tambah Barang</h1>

    <div class="card shadow-sm">
      <div class="card-body">
      <form action="{{ route('inventory.items.store') }}" method="POST">
        @csrf

        <div class="mb-3">
        <label for="category_id" class="form-label">Kategori <span class="text-danger">*</span></label>
        <select class="form-select @error('category_id') is-invalid @enderror" id="category_id" name="category_id"
          required>
          <option value="">Pilih Kategori</option>
          @foreach ($categories as $category)
        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
        {{ $category->name }}
        </option>
      @endforeach
        </select>
        @error('category_id')
      <div class="invalid-feedback">{{ $message }}</div>
      @enderror
        </div>

        {{-- Formulir Umum --}}
        <div id="general-fields" style="display: none;">
        <hr class="my-4">
        <h5 class="mb-3">Detail Barang Umum</h5>
        <div class="mb-3">
          <label for="general_name" class="form-label">Nama Produk <span class="text-danger">*</span></label>
          <input type="text" class="form-control @error('name') is-invalid @enderror" id="general_name" name="name"
          value="{{ old('name') }}">
          @error('name')
        <div class="invalid-feedback">{{ $message }}</div>
      @enderror
        </div>
        <div class="mb-3">
          <label for="general_price" class="form-label">Harga <span class="text-danger">*</span></label>
          <input type="number" class="form-control @error('price') is-invalid @enderror" id="general_price"
          name="price" value="{{ old('price') }}" min="0">
          @error('price')
        <div class="invalid-feedback">{{ $message }}</div>
      @enderror
        </div>
        <div class="mb-3">
          <label for="general_unit" class="form-label">Satuan <span class="text-danger">*</span></label>
          <input type="text" class="form-control @error('unit') is-invalid @enderror" id="general_unit" name="unit"
          value="{{ old('unit') }}">
          @error('unit')
        <div class="invalid-feedback">{{ $message }}</div>
      @enderror
        </div>
        <div class="mb-3">
          <label for="general_stock" class="form-label">Stok <span class="text-danger">*</span></label>
          <input type="number" class="form-control @error('stock') is-invalid @enderror" id="general_stock"
          name="stock" value="{{ old('stock') }}" min="0">
          @error('stock')
        <div class="invalid-feedback">{{ $message }}</div>
      @enderror
        </div>
        <div class="mb-3">
          <label for="general_description" class="form-label">Deskripsi (Opsional)</label>
          <textarea class="form-control @error('description') is-invalid @enderror" id="general_description"
          name="description" rows="3">{{ old('description') }}</textarea>
          @error('description')
        <div class="invalid-feedback">{{ $message }}</div>
      @enderror
        </div>
        </div>

        {{-- Formulir Spesifik untuk Cat --}}
        <div id="cat-fields" style="display: none;">
        <hr class="my-4">
        <h5 class="mb-3">Detail Barang Cat</h5>
        <div class="mb-3">
          <label for="cat_name" class="form-label">Nama Produk <span class="text-danger">*</span></label>
          <input type="text" class="form-control @error('name') is-invalid @enderror" id="cat_name" name="name"
          value="{{ old('name') }}">
          @error('name')
        <div class="invalid-feedback">{{ $message }}</div>
      @enderror
        </div>
        <div class="mb-3">
          <label for="paint_type" class="form-label">Jenis Cat <span class="text-danger">*</span></label>
          <input type="text" class="form-control @error('paint_type') is-invalid @enderror" id="paint_type"
          name="paint_type" value="{{ old('paint_type') }}" placeholder="Contoh: Cat Tembok, Cat Kayu & Besi">
          @error('paint_type')
        <div class="invalid-feedback">{{ $message }}</div>
      @enderror
        </div>
        <div class="mb-3">
          <label for="color_name" class="form-label">Nama Warna <span class="text-danger">*</span></label>
          <input type="text" class="form-control @error('color_name') is-invalid @enderror" id="color_name"
          name="color_name" value="{{ old('color_name') }}" placeholder="Contoh: Putih, Merah Maroon">
          @error('color_name')
        <div class="invalid-feedback">{{ $message }}</div>
      @enderror
        </div>
        <div class="mb-3">
          <label for="color_code" class="form-label">Kode Warna (Opsional)</label>
          <input type="text" class="form-control @error('color_code') is-invalid @enderror" id="color_code"
          name="color_code" value="{{ old('color_code') }}" placeholder="Contoh: #FF0000 atau RAL 3003">
          @error('color_code')
        <div class="invalid-feedback">{{ $message }}</div>
      @enderror
        </div>
        <div class="mb-3">
          <label for="volume" class="form-label">Berat/Volume (Kg/Liter) <span class="text-danger">*</span></label>
          <input type="text" class="form-control @error('volume') is-invalid @enderror" id="volume" name="volume"
          value="{{ old('volume') }}" placeholder="Contoh: 1 Liter, 5 Kg, 2.5 Liter">
          @error('volume')
        <div class="invalid-feedback">{{ $message }}</div>
      @enderror
        </div>
        <div class="mb-3">
          <label for="cat_price" class="form-label">Harga <span class="text-danger">*</span></label>
          <input type="number" class="form-control @error('price') is-invalid @enderror" id="cat_price" name="price"
          value="{{ old('price') }}" min="0">
          @error('price')
        <div class="invalid-feedback">{{ $message }}</div>
      @enderror
        </div>
        <div class="mb-3">
          <label for="cat_stock" class="form-label">Stok <span class="text-danger">*</span></label>
          <input type="number" class="form-control @error('stock') is-invalid @enderror" id="cat_stock" name="stock"
          value="{{ old('stock') }}" min="0">
          @error('stock')
        <div class="invalid-feedback">{{ $message }}</div>
      @enderror
        </div>
        <div class="mb-3">
          <label for="cat_description" class="form-label">Deskripsi (Opsional)</label>
          <textarea class="form-control @error('description') is-invalid @enderror" id="cat_description"
          name="description" rows="3">{{ old('description') }}</textarea>
          @error('description')
        <div class="invalid-feedback">{{ $message }}</div>
      @enderror
        </div>
        </div>

        {{-- Formulir Spesifik untuk Keramik --}}
        <div id="keramik-fields" style="display: none;">
        <hr class="my-4">
        <h5 class="mb-3">Detail Barang Keramik</h5>
        <div class="mb-3">
          <label for="keramik_name" class="form-label">Nama Produk <span class="text-danger">*</span></label>
          <input type="text" class="form-control @error('name') is-invalid @enderror" id="keramik_name" name="name"
          value="{{ old('name') }}">
          @error('name')
        <div class="invalid-feedback">{{ $message }}</div>
      @enderror
        </div>
        <div class="mb-3">
          <label for="size" class="form-label">Ukuran (cm) <span class="text-danger">*</span></label>
          <input type="text" class="form-control @error('size') is-invalid @enderror" id="size" name="size"
          value="{{ old('size') }}" placeholder="Contoh: 60x60 atau 30x30">
          @error('size')
        <div class="invalid-feedback">{{ $message }}</div>
      @enderror
        </div>
        <div class="mb-3">
          <label for="purchase_price" class="form-label">Harga Modal <span class="text-danger">*</span></label>
          <input type="number" class="form-control @error('purchase_price') is-invalid @enderror"
          id="purchase_price" name="purchase_price" value="{{ old('purchase_price') }}" min="0">
          @error('purchase_price')
        <div class="invalid-feedback">{{ $message }}</div>
      @enderror
        </div>
        <div class="mb-3">
          <label for="keramik_price" class="form-label">Harga Jual <span class="text-danger">*</span></label>
          <input type="number" class="form-control @error('price') is-invalid @enderror" id="keramik_price"
          name="price" value="{{ old('price') }}" min="0">
          @error('price')
        <div class="invalid-feedback">{{ $message }}</div>
      @enderror
        </div>
        <div class="mb-3">
          <label for="keramik_unit" class="form-label">Satuan <span class="text-danger">*</span></label>
          <input type="text" class="form-control @error('unit') is-invalid @enderror" id="keramik_unit" name="unit"
          value="{{ old('unit') }}">
          @error('unit')
        <div class="invalid-feedback">{{ $message }}</div>
      @enderror
        </div>
        <div class="mb-3">
          <label for="keramik_stock" class="form-label">Stok <span class="text-danger">*</span></label>
          <input type="number" class="form-control @error('stock') is-invalid @enderror" id="keramik_stock"
          name="stock" value="{{ old('stock') }}" min="0">
          @error('stock')
        <div class="invalid-feedback">{{ $message }}</div>
      @enderror
        </div>
        <div class="mb-3">
          <label for="keramik_description" class="form-label">Deskripsi (Opsional)</label>
          <textarea class="form-control @error('description') is-invalid @enderror" id="keramik_description"
          name="description" rows="3">{{ old('description') }}</textarea>
          @error('description')
        <div class="invalid-feedback">{{ $message }}</div>
      @enderror
        </div>
        </div>

        <button type="submit" class="btn btn-success btn-sm">Simpan Barang</button>
        <a href="{{ route('inventory.items') }}" class="btn btn-outline-secondary btn-sm">Batal</a>
      </form>
      </div>
    </div>
  </div>
@endsection

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
    function toggleInputAttributes(container, isRequired, inputNames, baseValues = {}) {
      const inputs = container.querySelectorAll('input, select, textarea');
      inputs.forEach(input => {
      let fieldName = input.name;
      // Jika nama sudah diubah menjadi 'hidden_fieldname', kembalikan ke nama asli
      if (fieldName.startsWith('hidden_')) {
        fieldName = fieldName.substring(7); // Hapus 'hidden_'
      }

      if (inputNames.includes(fieldName)) {
        if (isRequired) {
        // Setel atribut name kembali ke nama asli
        input.name = fieldName;
        // Setel required jika bidang ini memang wajib untuk kategori ini
        if (['name', 'price', 'unit', 'stock', 'size', 'purchase_price', 'paint_type', 'color_name', 'volume'].includes(fieldName)) {
          input.setAttribute('required', 'required');
          // Pastikan tanda bintang wajib ada
          const label = input.parentElement.querySelector('label');
          if (label && !label.querySelector('.text-danger')) {
          label.innerHTML += ' <span class="text-danger">*</span>';
          }
        }

        // Set nilai default dari old() atau baseValues saat berganti kategori
        if (baseValues.hasOwnProperty(fieldName) && baseValues[fieldName] !== null) {
          input.value = baseValues[fieldName];
        } else if (input.type === 'number' || input.tagName === 'TEXTAREA') {
          input.value = ''; // Kosongkan nilai numerik/textarea jika tidak ada old()
        } else {
          input.value = ''; // Kosongkan nilai string
        }

        } else {
        // Hapus atribut 'required' dan ubah 'name' agar tidak ikut ter-submit
        input.removeAttribute('required');
        input.name = 'hidden_' + fieldName; // Ubah nama untuk mencegah submit
        // Hapus tanda bintang wajib
        const label = input.parentElement.querySelector('label');
        if (label) {
          const span = label.querySelector('.text-danger');
          if (span) span.remove();
        }
        // Kosongkan nilai saat menyembunyikan
        input.value = '';
        }
      }
      // Hapus kelas is-invalid saat menyembunyikan form
      input.classList.remove('is-invalid');
      const invalidFeedback = input.nextElementSibling;
      if (invalidFeedback && invalidFeedback.classList.contains('invalid-feedback')) {
        invalidFeedback.style.display = 'none';
      }
      });
    }

    function showCategoryFields() {
      const selectedCategoryId = categorySelect.value;

      // Sembunyikan semua grup bidang terlebih dahulu
      generalFields.style.display = 'none';
      keramikFields.style.display = 'none';
      catFields.style.display = 'none';

      // Atur semua input di semua grup menjadi non-required dan ubah namanya
      toggleInputAttributes(generalFields, false, generalInputNames);
      toggleInputAttributes(keramikFields, false, keramikInputNames);
      toggleInputAttributes(catFields, false, catInputNames);

      let currentFieldsContainer;
      let currentInputNames;

      if (selectedCategoryId == catCategoryId) {
      currentFieldsContainer = catFields;
      currentInputNames = catInputNames;
      catFields.style.display = 'block';
      } else if (selectedCategoryId == keramikCategoryId) {
      currentFieldsContainer = keramikFields;
      currentInputNames = keramikInputNames;
      keramikFields.style.display = 'block';
      } else if (selectedCategoryId) { // Jika ada kategori yang dipilih, dan bukan Cat/Keramik (maka Umum)
      currentFieldsContainer = generalFields;
      currentInputNames = generalInputNames;
      generalFields.style.display = 'block';
      }

      // Aktifkan bidang untuk kategori yang dipilih
      if (currentFieldsContainer) {
      // Ambil old input untuk diisi kembali setelah perubahan kategori
      const oldValues = {};
      @if(old('category_id'))
      @foreach($allInputNames as $fieldName)
      oldValues['{{ $fieldName }}'] = "{{ old($fieldName) }}";
      @endforeach
    @endif
      toggleInputAttributes(currentFieldsContainer, true, currentInputNames, oldValues);
      }

      // Pastikan untuk menghapus kelas is-invalid dari semua input yang mungkin tersembunyi
      document.querySelectorAll('.form-control.is-invalid').forEach(input => {
      input.classList.remove('is-invalid');
      });
      document.querySelectorAll('.invalid-feedback').forEach(feedback => {
      feedback.style.display = 'none';
      });
    }

    // Jalankan saat halaman dimuat
    showCategoryFields();

    // Jalankan saat kategori diubah
    categorySelect.addEventListener('change', showCategoryFields);

    // Handle validasi setelah submit (jika ada error dari Laravel)
    // Ini akan memastikan form tetap terbuka di kategori yang benar setelah error
    @if($errors->any())
    const oldCategoryId = "{{ old('category_id') }}";
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