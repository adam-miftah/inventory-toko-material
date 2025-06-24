@extends('layouts.app')

@section('title', 'Import Data Barang')

@section('content')
  <div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">
      <i class="fas fa-file-excel text-primary me-2"></i>Import Data Barang
    </h4>
    <a href="{{ route('inventory.items') }}" class="btn btn-outline-secondary btn-sm">
      <i class="fas fa-arrow-left me-1"></i> Kembali
    </a>
    </div>

    @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
    <i class="fas fa-check-circle me-2"></i>
    <div>{{ session('success') }}</div>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
    <div class="d-flex align-items-center">
      <i class="fas fa-exclamation-circle me-2"></i>
      <div>
      <strong>Terjadi kesalahan saat impor:</strong>
      <ul class="mb-0">
      @foreach ($errors->all() as $error)
      <li>{{ $error }}</li>
      @endforeach
      @if ($errors->has('excel_errors'))
      @foreach ($errors->get('excel_errors') as $excelError)
      <li>{{ $excelError }}</li>
      @endforeach
      @endif
      </ul>
      </div>
    </div>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="card shadow-sm">
    <div class="card-body">
      <form action="{{ route('inventory.items.import.store') }}" method="POST" enctype="multipart/form-data">
      @csrf

      <div class="mb-3">
        <label for="category_type" class="form-label">Kategori Barang</label>
        <select class="form-select" id="category_type" name="category_type" required>
        {{-- PENTING: Ubah value="general" menjadi value="general" --}}
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
        <a href="#" id="downloadTemplate">Download template</a>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-primary">Import</button>
      </div>
      </form>
    </div>
    </div>
  </div>
@endsection