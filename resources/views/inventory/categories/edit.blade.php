@extends('layouts.app')
@section('title', 'Edit Jenis Barang')

@section('content')
  <div class="container-fluid">
    <h4 class="mb-4">Edit Jenis Barang ({{ $category->name }})</h4>

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
      <form action="{{ route('inventory.categories.update', $category) }}" method="POST">
      @csrf
      @method('PUT')
      <div class="mb-3">
        <label for="name" class="form-label">Nama Jenis Barang</label>
        <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $category->name) }}"
        required>
      </div>
      <div class="mb-3">
        <label for="type" class="form-label">Tipe Barang</label>
        <select class="form-select" id="type" name="type" required>
        <option value="generic" {{ old('type', $category->type) == 'generic' ? 'selected' : '' }}>Umum (misal: Kayu,
          Besi, dll.)</option>
        <option value="keramik" {{ old('type', $category->type) == 'keramik' ? 'selected' : '' }}>Keramik</option>
        <option value="cat" {{ old('type', $category->type) == 'cat' ? 'selected' : '' }}>Cat</option>
        </select>
        <div class="form-text">
        Pilih tipe barang. Keramik dan Cat memiliki kolom data spesifik.
        </div>
      </div>
      <button type="submit" class="btn btn-success btn-sm">Update</button>
      <a href="{{ route('inventory.categories') }}" class="btn btn-outline-secondary btn-sm">Batal</a>
      </form>
    </div>
    </div>
  </div>
@endsection