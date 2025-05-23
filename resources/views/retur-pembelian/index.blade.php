@extends('layouts.app')

@section('title', 'Daftar Retur Pembelian')

@section('content')
  <div class="container-fluid">
    <div class="page-header mb-4">
    <div class="d-flex justify-content-between align-items-center">
      <h4 class="page-title"><i class="fas fa-undo-alt me-2"></i> Daftar Retur Pembelian</h4>
      <a href="{{ route('retur-pembelian.create') }}" class="btn btn-outline-primary btn-sm">
      <i class="fas fa-plus"></i> Tambah Retur Pembelian
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
          <th>Nomor Retur</th>
          <th>Nama Barang</th>
          <th>Tanggal Retur</th>
          <th>Nomor Pembelian</th>
          <th>Total Retur</th>
          <th>Aksi</th>
        </tr>
        </thead>
        <tbody>
        @forelse ($returPembelians as $retur)
        <tr>
        <td>{{ $loop->iteration }}</td>
        <td>{{ $retur->id }}</td>
        <td>
        @foreach ($retur->items as $item)
        <span class="badge bg-info me-1">{{ $item->item_name }}</span>
      @endforeach
        </td>
        <td>{{ $retur->retur_date->format('d M Y') }}</td>
        <td><a href="{{ route('pembelian.show', $retur->pembelian) }}">{{ $retur->pembelian->id }}</a>
        </td>
        <td>{{ $retur->total_returned_amount_formatted }}</td>
        <td>
        <a href="{{ route('retur-pembelian.show', $retur) }}" class="btn btn-sm btn-info" title="Detail">
          <i class="fas fa-eye"></i>
        </a>
        <form action="{{ route('retur-pembelian.destroy', $retur) }}" method="POST" class="d-inline">
          @csrf
          @method('DELETE')
          <button type="submit" class="btn btn-sm btn-danger" title="Hapus"
          onclick="return confirm('Apakah Anda yakin ingin menghapus retur pembelian ini?')">
          <i class="fas fa-trash"></i>
          </button>
        </form>
        </td>
        </tr>
      @empty
      <tr>
        <td colspan="7" class="text-center">Tidak ada data retur pembelian.</td>
      </tr>
      @endforelse
        </tbody>
      </table>
      </div>

      {{ $returPembelians->links() }}
    </div>
    </div>
  </div>
@endsection