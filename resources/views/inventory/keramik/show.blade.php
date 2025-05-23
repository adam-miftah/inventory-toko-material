@extends('layouts.app')

@section('content')
  <div class="container-fluid">
    <h1 class="mb-4">Detail Keramik: {{ $keramik->name }} (ID: {{ $keramik->id }})</h1>

    <div class="card shadow-sm">
    <div class="card-body">
      <div class="row mb-3">
      <div class="col-md-4"><strong>ID Produk:</strong></div>
      <div class="col-md-8">{{ $keramik->id }}</div>
      </div>
      <div class="row mb-3">
      <div class="col-md-4"><strong>Nama Produk:</strong></div>
      <div class="col-md-8">{{ $keramik->name }}</div>
      </div>
      <div class="row mb-3">
      <div class="col-md-4"><strong>Ukuran:</strong></div>
      <div class="col-md-8">{{ $keramik->size }}</div>
      </div>
      <div class="row mb-3">
      <div class="col-md-4"><strong>Harga Modal:</strong></div>
      <div class="col-md-8">Rp {{ number_format($keramik->purchase_price, 0, ',', '.') }}</div>
      </div>
      <div class="row mb-3">
      <div class="col-md-4"><strong>Harga Jual:</strong></div>
      <div class="col-md-8">Rp {{ number_format($keramik->selling_price, 0, ',', '.') }}</div>
      </div>
      <div class="row mb-3">
      <div class="col-md-4"><strong>Satuan:</strong></div>
      <div class="col-md-8">{{ $keramik->unit }}</div>
      </div>
      <div class="row mb-3">
      <div class="col-md-4"><strong>Stok:</strong></div>
      <div class="col-md-8">{{ $keramik->stock }}</div>
      </div>
      <div class="row mb-3">
      <div class="col-md-4"><strong>Dibuat Pada:</strong></div>
      <div class="col-md-8">{{ $keramik->created_at->format('d M Y H:i') }}</div>
      </div>
      <div class="row mb-3">
      <div class="col-md-4"><strong>Terakhir Diperbarui:</strong></div>
      <div class="col-md-8">{{ $keramik->updated_at->format('d M Y H:i') }}</div>
      </div>
      <div class="mt-4">
      <a href="{{ route('inventory.keramiks.edit', $keramik) }}" class="btn btn-warning me-2">Edit</a>
      <a href="{{ route('inventory.keramiks') }}" class="btn btn-secondary">Kembali ke Daftar</a>
      </div>
    </div>
    </div>
  </div>
@endsection