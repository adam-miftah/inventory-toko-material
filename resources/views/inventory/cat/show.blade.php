@extends('layouts.app')

@section('content')
  <div class="container-fluid">
    <h1 class="mb-4">Detail Cat: {{ $cat->name }} (ID: {{ $cat->id }})</h1>

    <div class="card shadow-sm">
    <div class="card-body">
      <div class="row mb-3">
      <div class="col-md-4"><strong>ID Produk:</strong></div>
      <div class="col-md-8">{{ $cat->id }}</div>
      </div>
      <div class="row mb-3">
      <div class="col-md-4"><strong>Nama Produk:</strong></div>
      <div class="col-md-8">{{ $cat->name }}</div>
      </div>
      <div class="row mb-3">
      <div class="col-md-4"><strong>Jenis Cat:</strong></div>
      <div class="col-md-8">{{ $cat->type_of_paint }}</div>
      </div>
      <div class="row mb-3">
      <div class="col-md-4"><strong>Warna:</strong></div>
      <div class="col-md-8">{{ $cat->color }}</div>
      </div>
      <div class="row mb-3">
      <div class="col-md-4"><strong>Kode:</strong></div>
      <div class="col-md-8">{{ $cat->code ?? '-' }}</div>
      </div>
      <div class="row mb-3">
      <div class="col-md-4"><strong>Berat:</strong></div>
      <div class="col-md-8">{{ $cat->weight }}</div>
      </div>
      <div class="row mb-3">
      <div class="col-md-4"><strong>Harga:</strong></div>
      <div class="col-md-8">Rp {{ number_format($cat->price, 0, ',', '.') }}</div>
      </div>
      <div class="row mb-3">
      <div class="col-md-4"><strong>Stok:</strong></div>
      <div class="col-md-8">{{ $cat->stock }}</div>
      </div>
      <div class="row mb-3">
      <div class="col-md-4"><strong>Dibuat Pada:</strong></div>
      <div class="col-md-8">{{ $cat->created_at->format('d M Y H:i') }}</div>
      </div>
      <div class="row mb-3">
      <div class="col-md-4"><strong>Terakhir Diperbarui:</strong></div>
      <div class="col-md-8">{{ $cat->updated_at->format('d M Y H:i') }}</div>
      </div>
      <div class="mt-4">
      <a href="{{ route('inventory.cats.edit', $cat) }}" class="btn btn-warning me-2">Edit</a>
      <a href="{{ route('inventory.cats') }}" class="btn btn-secondary">Kembali ke Daftar</a>
      </div>
    </div>
    </div>
  </div>
@endsection