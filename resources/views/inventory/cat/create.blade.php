@extends('layouts.app')

@section('content')
  <div class="container-fluid">
    <h1 class="mb-4">Tambah Cat Baru</h1>

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
      <form action="{{ route('inventory.cats.store') }}" method="POST">
      @csrf
      <div class="mb-3">
        <label for="name" class="form-label">Nama Produk</label>
        <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
      </div>
      <div class="mb-3">
        <label for="type_of_paint" class="form-label">Jenis Cat</label>
        <input type="text" class="form-control" id="type_of_paint" name="type_of_paint"
        value="{{ old('type_of_paint') }}" placeholder="contoh: Tembok, Kayu, Besi" required>
      </div>
      <div class="mb-3">
        <label for="color" class="form-label">Warna</label>
        <input type="text" class="form-control" id="color" name="color" value="{{ old('color') }}" required>
      </div>
      <div class="mb-3">
        <label for="code" class="form-label">Kode (Opsional)</label>
        <input type="text" class="form-control" id="code" name="code" value="{{ old('code') }}">
      </div>
      <div class="mb-3">
        <label for="weight" class="form-label">Berat (kg/liter)</label>
        <input type="number" class="form-control" id="weight" name="weight" value="{{ old('weight') }}" step="0.01"
        required>
      </div>
      <div class="mb-3">
        <label for="price" class="form-label">Harga</label>
        <input type="number" class="form-control" id="price" name="price" value="{{ old('price') }}" step="0.01"
        required>
      </div>
      <div class="mb-3">
        <label for="stock" class="form-label">Stok</label>
        <input type="number" class="form-control" id="stock" name="stock" value="{{ old('stock') }}" required>
      </div>
      <button type="submit" class="btn btn-primary">Simpan</button>
      <a href="{{ route('inventory.cats') }}" class="btn btn-secondary">Batal</a>
      </form>
    </div>
    </div>
  </div>
@endsection