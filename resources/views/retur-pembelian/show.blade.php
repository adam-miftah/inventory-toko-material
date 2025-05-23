@extends('layouts.app')

@section('title', 'Detail Retur Pembelian')

@section('content')
  <div class="container-fluid">
    <div class="page-header mb-4">
    <div class="d-flex justify-content-between align-items-center">
      <h4 class="page-title"><i class="fas fa-undo-alt me-2"></i> Detail Retur Pembelian</h4>
      <form action="{{ route('retur-pembelian.destroy', $returPembelian) }}" method="POST" class="d-inline">
      @csrf
      @method('DELETE')
      <button type="submit" class="btn btn-danger btn-sm"
        onclick="return confirm('Apakah Anda yakin ingin menghapus retur pembelian ini?')">
        <i class="fas fa-trash"></i> Hapus Retur
      </button>
      </form>
    </div>
    </div>

    <div class="card shadow-sm">
    <div class="card-body">
      <h5 class="card-title">Informasi Retur Pembelian</h5>
      <dl class="row">
      <dt class="col-sm-3">Nomor Retur</dt>
      <dd class="col-sm-9">{{ $returPembelian->id }}</dd>

      <dt class="col-sm-3">Tanggal Retur</dt>
      <dd class="col-sm-9">{{ $returPembelian->retur_date->format('d M Y') }}</dd>

      <dt class="col-sm-3">Nomor Pembelian</dt>
      <dd class="col-sm-9"><a
        href="{{ route('pembelian.show', $returPembelian->pembelian) }}">{{ $returPembelian->pembelian->id }}</a>
      </dd>

      <dt class="col-sm-3">Total Retur</dt>
      <dd class="col-sm-9">{{ $returPembelian->total_returned_amount_formatted }}</dd>

      <dt class="col-sm-3">Catatan</dt>
      <dd class="col-sm-9">{{ $returPembelian->notes ?? '-' }}</dd>

      <dt class="col-sm-3">Dibuat Oleh</dt>
      <dd class="col-sm-9">{{ $returPembelian->user->name }}</dd>
      </dl>

      <h5 class="mt-4">Detail Item yang Diretur</h5>
      @if ($returPembelian->items->isNotEmpty())
      <div class="table-responsive">
      <table class="table table-bordered table-hover">
      <thead class="thead-light">
      <tr>
        <th>ID</th>
        <th>Nama Produk</th>
        <th>Kuantitas Retur</th>
        <th>Harga Satuan</th>
        <th>Subtotal Retur</th>
      </tr>
      </thead>
      <tbody>
      @foreach ($returPembelian->items as $returItem)
      <tr>
      <td>{{ $loop->iteration }}</td>
      <td>{{ $returItem->item_name }}</td>
      <td>{{ $returItem->quantity }}</td>
      <td>{{ $returItem->unit_price_formatted }}</td>
      <td>{{ $returItem->subtotal_returned_formatted }}</td>
      </tr>
      @endforeach
      </tbody>
      </table>
      </div>
    @else
      <p>Tidak ada item yang diretur dalam retur pembelian ini.</p>
    @endif

      <div class="mt-4">
      <a href="{{ route('retur-pembelian.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="fas fa-arrow-left"></i> Kembali
      </a>
      </div>
    </div>
    </div>
  </div>
@endsection