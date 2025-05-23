@extends('layouts.app')

@section('content')
  <div class="container-fluid">
    <h1 class="mb-4">Tambah Keramik Baru</h1>

    @if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
    <strong>Terjadi kesalahan!</strong>
    <ul>
      @foreach ($errors->all() as $error)
      <li>{{ $error }}</li>
    @endforeach
    </ul>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="card shadow-sm">
    <div class="card-body">
      <form action="{{ route('inventory.keramiks.store') }}" method="POST">
      @csrf
      <div class="mb-3">
        <label for="name" class="form-label">Nama Produk</label>
        <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
      </div>
      <div class="mb-3">
        <label for="size" class="form-label">Ukuran</label>
        <input type="text" class="form-control" id="size" name="size" value="{{ old('size') }}"
        placeholder="contoh: 60x60 cm" required>
      </div>
      <div class="mb-3">
        <label for="purchase_price" class="form-label">Harga Modal</label>
        <input type="number" class="form-control" id="purchase_price" name="purchase_price"
        value="{{ old('purchase_price') }}" step="0.01" required>
      </div>
      <div class="mb-3">
        <label for="selling_price" class="form-label">Harga Jual</label>
        <input type="number" class="form-control" id="selling_price" name="selling_price"
        value="{{ old('selling_price') }}" step="0.01" required>
      </div>
      <div class="mb-3">
        <label for="unit" class="form-label">Satuan</label>
        <input type="text" class="form-control" id="unit" name="unit" value="{{ old('unit') }}"
        placeholder="contoh: dus, meter persegi" required>
      </div>
      <div class="mb-3">
        <label for="stock" class="form-label">Stok</label>
        <input type="number" class="form-control" id="stock" name="stock" value="{{ old('stock') }}" required>
      </div>
      <button type="submit" class="btn btn-primary">Simpan</button>
      <a href="{{ route('inventory.keramiks') }}" class="btn btn-secondary">Batal</a>
      </form>
    </div>
    </div>
  </div>
@endsection