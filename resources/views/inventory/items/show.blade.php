@extends('layouts.app')

@section('title', 'Detail Barang')

@section('content')
  <div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">
      <i class="fas fa-box-open text-primary me-2"></i>Detail Barang
    </h4>
    <div>
      <a href="{{ route('inventory.items') }}" class="btn btn-outline-secondary btn-sm">
      <i class="fas fa-arrow-left me-1"></i> Kembali
      </a>
    </div>
    </div>

    <div class="card shadow-sm mb-4">
    <div class="card-header 
      @if(stripos($item->category->name ?? '', 'cat') !== false) bg-warning text-dark
    @elseif(stripos($item->category->name ?? '', 'keramik') !== false) bg-danger text-white
    @else bg-primary text-white @endif">
      <h5 class="mb-0">
      @if(stripos($item->category->name ?? '', 'cat') !== false)
      <i class="fas fa-paint-roller me-2"></i>
    @elseif(stripos($item->category->name ?? '', 'keramik') !== false)
      <i class="fas fa-border-style me-2"></i>
    @else
      <i class="fas fa-cube me-2"></i>
    @endif
      Informasi Produk
      </h5>
    </div>
    <div class="card-body">
      <div class="row mb-3">
      <div class="col-md-4 fw-bold">ID Barang:</div>
      <div class="col-md-8">
        <span class="badge bg-primary bg-opacity-10 text-primary">#{{ $item->id }}</span>
      </div>
      </div>
      <div class="row mb-3">
      <div class="col-md-4 fw-bold">Nama Produk:</div>
      <div class="col-md-8">{{ $item->name }}</div>
      </div>
      <div class="row mb-3">
      <div class="col-md-4 fw-bold">Kategori:</div>
      <div class="col-md-8">
        <span class="badge bg-info bg-opacity-10 text-info">
        {{ $item->category->name ?? 'N/A' }}
        </span>
      </div>
      </div>

      @if (stripos($item->category->name ?? '', 'cat') !== false)
      <div class="detail-section mt-4">
      <h5 class="section-title mb-3">
      <i class="fas fa-paint-brush me-2"></i>Detail Cat
      </h5>
      <div class="row mb-3">
      <div class="col-md-4 fw-bold">Jenis Cat:</div>
      <div class="col-md-8">{{ $item->paint_type ?? '-' }}</div>
      </div>
      <div class="row mb-3">
      <div class="col-md-4 fw-bold">Nama Warna:</div>
      <div class="col-md-8">
      <div class="d-flex align-items-center">
        @if($item->color_code)
      <div class="flex-shrink-0 me-2">
      <div
      style="width: 20px; height: 20px; background-color: {{ $item->color_code }}; border: 1px solid #ddd; border-radius: 3px;">
      </div>
      </div>
      @endif
        <div class="flex-grow-1">
        {{ $item->color_name ?? '-' }}
        </div>
      </div>
      </div>
      </div>
      <div class="row mb-3">
      <div class="col-md-4 fw-bold">Kode Warna:</div>
      <div class="col-md-8">
      @if($item->color_code)
      <span class="badge bg-light text-dark">{{ $item->color_code }}</span>
      @else
      -
      @endif
      </div>
      </div>
      <div class="row mb-3">
      <div class="col-md-4 fw-bold">Berat/Volume:</div>
      <div class="col-md-8">{{ $item->volume ?? '-' }}</div>
      </div>
      <div class="row mb-3">
      <div class="col-md-4 fw-bold">Harga:</div>
      <div class="col-md-8 text-success fw-bold">Rp {{ number_format($item->price, 0, ',', '.') }}</div>
      </div>
      <div class="row mb-3">
      <div class="col-md-4 fw-bold">Stok:</div>
      <div class="col-md-8">
      <span
        class="badge bg-{{ $item->stock > 0 ? 'success' : 'danger' }}-subtle text-{{ $item->stock > 0 ? 'success' : 'danger' }}">
        {{ $item->stock }}
      </span>
      </div>
      </div>
      <div class="row mb-3">
      <div class="col-md-4 fw-bold">Deskripsi:</div>
      <div class="col-md-8">{{ $item->description ?? '-' }}</div>
      </div>
      </div>
    @elseif (stripos($item->category->name ?? '', 'keramik') !== false)
      <div class="detail-section mt-4">
      <h5 class="section-title mb-3">
      <i class="fas fa-border-all me-2"></i>Detail Keramik
      </h5>
      <div class="row mb-3">
      <div class="col-md-4 fw-bold">Ukuran:</div>
      <div class="col-md-8">{{ $item->size ?? '-' }}</div>
      </div>
      <div class="row mb-3">
      <div class="col-md-4 fw-bold">Harga Modal:</div>
      <div class="col-md-8">Rp {{ number_format($item->purchase_price, 0, ',', '.') }}</div>
      </div>
      <div class="row mb-3">
      <div class="col-md-4 fw-bold">Harga Jual:</div>
      <div class="col-md-8 text-success fw-bold">Rp {{ number_format($item->price, 0, ',', '.') }}</div>
      </div>
      <div class="row mb-3">
      <div class="col-md-4 fw-bold">Satuan:</div>
      <div class="col-md-8">{{ $item->unit ?? '-' }}</div>
      </div>
      <div class="row mb-3">
      <div class="col-md-4 fw-bold">Stok:</div>
      <div class="col-md-8">
      <span
        class="badge bg-{{ $item->stock > 0 ? 'success' : 'danger' }}-subtle text-{{ $item->stock > 0 ? 'success' : 'danger' }}">
        {{ $item->stock }}
      </span>
      </div>
      </div>
      <div class="row mb-3">
      <div class="col-md-4 fw-bold">Deskripsi:</div>
      <div class="col-md-8">{{ $item->description ?? '-' }}</div>
      </div>
      </div>
    @else
      <div class="detail-section mt-4">
      <h5 class="section-title mb-3">
      <i class="fas fa-info-circle me-2"></i>Detail Barang Umum
      </h5>
      <div class="row mb-3">
      <div class="col-md-4 fw-bold">Harga:</div>
      <div class="col-md-8 text-success fw-bold">Rp {{ number_format($item->price, 0, ',', '.') }}</div>
      </div>
      <div class="row mb-3">
      <div class="col-md-4 fw-bold">Satuan:</div>
      <div class="col-md-8">{{ $item->unit }}</div>
      </div>
      <div class="row mb-3">
      <div class="col-md-4 fw-bold">Stok:</div>
      <div class="col-md-8">
      <span
        class="badge bg-{{ $item->stock > 0 ? 'success' : 'danger' }}-subtle text-{{ $item->stock > 0 ? 'success' : 'danger' }}">
        {{ $item->stock }}
      </span>
      </div>
      </div>
      <div class="row mb-3">
      <div class="col-md-4 fw-bold">Deskripsi:</div>
      <div class="col-md-8">{{ $item->description ?? '-' }}</div>
      </div>
      </div>
    @endif

      <div class="mt-4 pt-3 border-top">
      <a href="{{ route('inventory.items.edit', $item->id) }}" class="btn btn-warning me-2 btn-sm">
        <i class="fas fa-edit me-1"></i> Edit
      </a>
      <a href="{{ route('inventory.items') }}" class="btn btn-outline-secondary btn-sm">
        <i class="fas fa-list me-1"></i> Daftar Barang
      </a>
      </div>
    </div>
    </div>
  </div>
@endsection

@push('styles')
  <style>
    .card-header {
    border-bottom: none;
    padding: 1rem 1.5rem;
    }

    .detail-section {
    background-color: #f8f9fa;
    border-radius: 0.5rem;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
    }

    .section-title {
    color: #495057;
    font-size: 1.1rem;
    padding-bottom: 0.5rem;
    border-bottom: 1px solid #dee2e6;
    }

    .badge {
    font-weight: 500;
    padding: 0.35em 0.65em;
    }

    .row {
    padding: 0.5rem 0;
    }

    .row div:first-child {
    color: #6c757d;
    }

    @media (max-width: 767.98px) {
    .row {
      margin-bottom: 1rem !important;
    }

    .row div {
      padding-bottom: 0.25rem;
    }

    .row div:first-child {
      font-weight: 600;
      border-bottom: 1px dashed #dee2e6;
      margin-bottom: 0.25rem;
      padding-bottom: 0.25rem;
    }

    .detail-section {
      padding: 1rem;
    }
    }
  </style>
@endpush