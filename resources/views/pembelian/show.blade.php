@extends('layouts.app')

@section('title', 'Detail Pembelian')

@section('content')
  <div class="container-fluid">
    <div class="page-header mb-4">
    <div class="d-flex justify-content-between align-items-center">
      <h4 class="page-title"><i class="fas fa-truck-loading me-2"></i> Detail Pembelian</h4>
      <div>
      <a href="{{ route('pembelian.edit', $pembelian) }}" class="btn btn-warning btn-sm">
        <i class="fas fa-edit"></i> Edit Pembelian
      </a>
      <form action="{{ route('pembelian.destroy', $pembelian) }}" method="POST" class="d-inline">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger btn-sm"
        onclick="return confirm('Apakah Anda yakin ingin menghapus pembelian ini?')">
        <i class="fas fa-trash"></i> Hapus Pembelian
        </button>
      </form>
      </div>
    </div>
    </div>

    <div class="card shadow-sm">
    <div class="card-body">
      <h5 class="card-title">Informasi Pembelian</h5>
      <dl class="row">
      <dt class="col-sm-3">Nomor Pembelian</dt>
      <dd class="col-sm-9">{{ $pembelian->id }}</dd>

      <dt class="col-sm-3">Tanggal Pembelian</dt>
      <dd class="col-sm-9">{{ $pembelian->purchase_date->format('d M Y') }}</dd>

      <dt class="col-sm-3">Supplier</dt>
      <dd class="col-sm-9"><a
        href="{{ route('supplier.show', $pembelian->supplier) }}">{{ $pembelian->supplier->name }}</a></dd>

      <dt class="col-sm-3">Total Pembelian</dt>
      <dd class="col-sm-9">{{ $pembelian->total_amount_formatted }}</dd>

      <dt class="col-sm-3">Catatan</dt>
      <dd class="col-sm-9">{{ $pembelian->notes ?? '-' }}</dd>

      <dt class="col-sm-3">Dibuat Oleh</dt>
      <dd class="col-sm-9">{{ $pembelian->user->name }}</dd>
      </dl>

      <h5 class="mt-4">Detail Item Pembelian</h5>
      @if ($pembelian->items->isNotEmpty())
      <div class="table-responsive">
      <table class="table table-bordered table-hover">
      <thead class="thead-light">
      <tr>
        <th>ID</th>
        <th>Nama Produk</th>
        <th>Kuantitas</th>
        <th>Harga Satuan</th>
        <th>Subtotal</th>
      </tr>
      </thead>
      <tbody>
      @foreach ($pembelian->items as $itemPembelian)
      <tr>
      <td>{{ $loop->iteration }}</td>
      <td><a href="{{ route('item.show', $itemPembelian->item) }}">{{ $itemPembelian->item_name }}</a></td>
      <td>{{ $itemPembelian->quantity }}</td>
      <td>{{ $itemPembelian->unit_price_formatted }}</td>
      <td>{{ $itemPembelian->subtotal_formatted }}</td>
      </tr>
      @endforeach
      </tbody>
      </table>
      </div>
    @else
      <p>Tidak ada item dalam pembelian ini.</p>
    @endif

      <div class="mt-4">
      <a href="{{ route('pembelian.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="fas fa-arrow-left"></i> Kembali
      </a>
      </div>
    </div>
    </div>
  </div>
@endsection