@extends('layouts.app')

@section('title', 'Daftar Pembelian')

@section('content')
  <div class="container-fluid">
    <div class="page-header mb-4">
    <div class="d-flex justify-content-between align-items-center">
      <h4 class="page-title"><i class="fas fa-truck-loading me-2"></i> Daftar Pembelian</h4>
      <a href="{{ route('pembelian.create') }}" class="btn btn-outline-primary btn-sm">
      <i class="fas fa-plus"></i> Tambah Pembelian Baru
      </a>
    </div>
    </div>

    <div class="card shadow-sm">
    <div class="card-body">
      @if (session('success'))
      <div class="alert alert-success mb-3">{{ session('success') }}</div>
    @endif

      <div class="table-responsive">
      <table class="table table-bordered table-hover">
        <thead class="thead-light">
        <tr>
          <th>ID</th>
          <th>Nomor Pembelian</th>
          <th>Nama Barang</th>
          <th>Tanggal</th>
          <th>Supplier</th>
          <th>Total</th>
          <th>Aksi</th>
        </tr>
        </thead>
        <tbody>
        @forelse ($pembelians as $pembelian)
        <tr>
        <td>{{ $loop->iteration }}</td>
        <td>{{ $pembelian->id }}</td>
        @foreach ($pembelian->items as $item)
        <span class="badge bg-info me-1">{{ $item->item_name }}</span>
      @endforeach
        <td>{{ $pembelian->purchase_date->format('d M Y') }}</td>
        <td>{{ $pembelian->supplier->name }}</td>
        <td>
        </td>
        <td>{{ $pembelian->total_amount_formatted }}</td>
        <td>
        <a href="{{ route('pembelian.show', $pembelian) }}" class="btn btn-sm btn-info" title="Detail">
          <i class="fas fa-eye"></i>
        </a>
        <a href="{{ route('pembelian.edit', $pembelian) }}" class="btn btn-sm btn-warning" title="Edit">
          <i class="fas fa-edit"></i>
        </a>
        <form action="{{ route('pembelian.destroy', $pembelian) }}" method="POST" class="d-inline">
          @csrf
          @method('DELETE')
          <button type="submit" class="btn btn-sm btn-danger" title="Hapus"
          onclick="return confirm('Apakah Anda yakin ingin menghapus pembelian ini?')">
          <i class="fas fa-trash"></i>
          </button>
        </form>
        </td>
        </tr>
      @empty
      <tr>
        <td colspan="7" class="text-center">Tidak ada data pembelian.</td>
      </tr>
      @endforelse
        </tbody>
      </table>
      </div>

      {{ $pembelians->links() }}
    </div>
    </div>
  </div>
@endsection