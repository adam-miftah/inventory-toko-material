@extends('layouts.app')

@section('content')
  <div class="container-fluid">
    <h1 class="mb-4">Daftar Barang (Cat)</h1>

    @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <a href="{{ route('inventory.cats.create') }}" class="btn btn-primary mb-3">Tambah Cat Baru</a>

    <div class="card shadow-sm">
    <div class="card-body">
      <div class="table-responsive">
      <table class="table table-hover table-striped">
        <thead>
        <tr>
          <th>ID</th>
          <th>Nama Produk</th>
          <th>Jenis</th>
          <th>Warna</th>
          <th>Kode</th>
          <th>Berat</th>
          <th>Harga</th>
          <th>Stok</th>
          <th>Aksi</th>
        </tr>
        </thead>
        <tbody>
        @forelse ($cats as $cat)
      <tr>
        <td>{{ $cat->id }}</td>
        <td>{{ $cat->name }}</td>
        <td>{{ $cat->type_of_paint }}</td>
        <td>{{ $cat->color }}</td>
        <td>{{ $cat->code ?? '-' }}</td>
        <td>{{ $cat->weight }}</td>
        <td>Rp {{ number_format($cat->price, 0, ',', '.') }}</td>
        <td>{{ $cat->stock }}</td>
        <td>
        <a href="{{ route('inventory.cats.show', $cat) }}" class="btn btn-info btn-sm me-2">Detail</a>
        <a href="{{ route('inventory.cats.edit', $cat) }}" class="btn btn-warning btn-sm me-2">Edit</a>
        <form action="{{ route('inventory.cats.destroy', $cat) }}" method="POST" class="d-inline"
        onsubmit="return confirm('Apakah Anda yakin ingin menghapus data cat ini?');">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
        </form>
        </td>
      </tr>
      @empty
      <tr>
        <td colspan="9" class="text-center">Tidak ada data cat yang ditemukan.</td>
      </tr>
      @endforelse
        </tbody>
      </table>
      </div>
    </div>
    </div>
  </div>
@endsection