@extends('layouts.app')

@section('content')
  <div class="container-fluid">
    <h1 class="mb-4">Daftar Barang (Keramik)</h1>

    @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <a href="{{ route('inventory.keramiks.create') }}" class="btn btn-primary mb-3">Tambah Keramik Baru</a>

    <div class="card shadow-sm">
    <div class="card-body">
      <div class="table-responsive">
      <table class="table table-hover table-striped">
        <thead>
        <tr>
          <th>ID</th>
          <th>Nama Produk</th>
          <th>Ukuran</th>
          <th>Harga Modal</th>
          <th>Harga Jual</th>
          <th>Satuan</th>
          <th>Stok</th>
          <th>Aksi</th>
        </tr>
        </thead>
        <tbody>
        @forelse ($keramiks as $keramik)
      <tr>
        <td>{{ $keramik->id }}</td>
        <td>{{ $keramik->name }}</td>
        <td>{{ $keramik->size }}</td>
        <td>Rp {{ number_format($keramik->purchase_price, 0, ',', '.') }}</td>
        <td>Rp {{ number_format($keramik->selling_price, 0, ',', '.') }}</td>
        <td>{{ $keramik->unit }}</td>
        <td>{{ $keramik->stock }}</td>
        <td>
        <a href="{{ route('inventory.keramiks.show', $keramik) }}" class="btn btn-info btn-sm me-2">Detail</a>
        <a href="{{ route('inventory.keramiks.edit', $keramik) }}" class="btn btn-warning btn-sm me-2">Edit</a>
        <form action="{{ route('inventory.keramiks.destroy', $keramik) }}" method="POST" class="d-inline"
        onsubmit="return confirm('Apakah Anda yakin ingin menghapus data keramik ini?');">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
        </form>
        </td>
      </tr>
      @empty
      <tr>
        <td colspan="8" class="text-center">Tidak ada data keramik yang ditemukan.</td>
      </tr>
      @endforelse
        </tbody>
      </table>
      </div>
    </div>
    </div>
  </div>
@endsection