@extends('layouts.app')

@section('title', 'Detail Barang')

@section('content')
    <div class="container-fluid">
        {{-- HEADER HALAMAN --}}
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
            <h4 class="mb-3 mb-md-0 fw-bold text-gradient">
                <i class="fas fa-box-open me-2"></i> Detail Barang
            </h4>
            <div>
                <a href="{{ route('inventory.items.edit', $item->id) }}" class="btn btn-warning btn-sm">
                    <i class="fas fa-edit me-1"></i> Edit
                </a>
                <a href="{{ route('inventory.items.index') }}" class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left me-1"></i> Kembali
                </a>
            </div>
        </div>

        {{-- KONTEN UTAMA --}}
        <div class="row g-4">
            {{-- KOLOM KIRI: INFORMASI UTAMA & DETAIL SPESIFIK --}}
            <div class="col-lg-8">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header bg-white p-3 d-flex align-items-center">
                        @php
                            $categoryType = strtolower($item->category->type ?? 'general');
                            $icon = 'fa-cube';
                            if ($categoryType === 'cat')
                                $icon = 'fa-paint-roller';
                            if ($categoryType === 'keramik')
                                $icon = 'fa-border-style';
                            if ($categoryType === 'luar')
                                $icon = 'fa-shipping-fast';
                        @endphp
                        <i class="fas {{ $icon }} fs-4 text-primary me-3"></i>
                        <div>
                            <h5 class="mb-0 fw-bold">{{ $item->name }}</h5>
                            <small class="text-muted">ID Barang: #{{ $item->id }}</small>
                        </div>
                    </div>
                    <div class="card-body">
                        {{-- Informasi Umum --}}
                        <div class="row mb-3">
                            <div class="col-md-4 text-muted">Kategori</div>
                            <div class="col-md-8 fw-semibold">
                                <span
                                    class="badge bg-primary-subtle text-primary-emphasis">{{ $item->category->name ?? 'N/A' }}</span>
                            </div>
                        </div>
                        @if($item->description)
                            <div class="row mb-3">
                                <div class="col-md-4 text-muted">Deskripsi</div>
                                <div class="col-md-8">{{ $item->description }}</div>
                            </div>
                        @endif
                        <hr>

                        {{-- Detail Spesifik Berdasarkan Kategori --}}
                        @if ($categoryType === 'cat')
                            <div class="row mb-2">
                                <div class="col-md-4 text-muted">Jenis Cat</div>
                                <div class="col-md-8">{{ $item->paint_type ?? '-' }}</div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-4 text-muted">Nama Warna</div>
                                <div class="col-md-8">{{ $item->color_name ?? '-' }}</div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-4 text-muted">Kode Warna</div>
                                <div class="col-md-8">{{ $item->color_code ?? '-' }}</div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-4 text-muted">Volume</div>
                                <div class="col-md-8">{{ $item->volume ?? '-' }}</div>
                            </div>
                        @elseif ($categoryType === 'keramik')
                            <div class="row mb-2">
                                <div class="col-md-4 text-muted">Ukuran</div>
                                <div class="col-md-8">{{ $item->size ?? '-' }} cm</div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-4 text-muted">Satuan</div>
                                <div class="col-md-8">{{ $item->unit ?? '-' }}</div>
                            </div>
                        @else {{-- general, luar, etc --}}
                            <div class="row mb-2">
                                <div class="col-md-4 text-muted">Satuan</div>
                                <div class="col-md-8">{{ $item->unit ?? '-' }}</div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- KOLOM KANAN: STOK & HARGA --}}
            <div class="col-lg-4">
                {{-- Kartu Stok --}}
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white p-3">
                        <h5 class="mb-0 fw-semibold"><i class="fas fa-warehouse me-2 text-info"></i>Stok</h5>
                    </div>
                    <div class="card-body text-center">
                        <h1 class="display-4 fw-bolder mb-0">{{ $item->stock }}</h1>
                        <p class="text-muted mb-0">Stok Saat Ini</p>
                    </div>
                </div>

                {{-- Kartu Harga --}}
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white p-3">
                        <h5 class="mb-0 fw-semibold"><i class="fas fa-dollar-sign me-2 text-success"></i>Harga</h5>
                    </div>
                    <ul class="list-group list-group-flush">
                        {{-- PERBAIKAN: Menghapus kondisi @if, agar harga modal selalu tampil --}}
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span class="text-muted">Harga Modal</span>
                            <span class="fw-semibold">Rp {{ number_format($item->purchase_price ?? 0, 0, ',', '.') }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span class="text-muted">Harga Jual</span>
                            <span class="fw-bold fs-5 text-success">Rp {{ number_format($item->price, 0, ',', '.') }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
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
    </style>
@endpush