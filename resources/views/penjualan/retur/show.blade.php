@extends('layouts.app')

@section('title', 'Detail Retur Penjualan')

@section('content')
  <div class="container-fluid">
    <h4 class="mb-4">Detail Retur Penjualan: {{ $retur->return_number }}</h4>

    <div class="card shadow-sm mb-4">
    <div class="card-header bg-primary text-white">
      Informasi Retur
    </div>
    <div class="card-body">
      <div class="row">
      <div class="col-md-6">
        <p><strong>Nomor Retur:</strong> {{ $retur->return_number }}</p>
        <p>
        <strong>Tanggal Retur:</strong>
        @if ($retur->return_date)
      {{ $retur->return_date->format('d M Y H:i') }}
      @else
      N/A
      @endif
        </p>
        <p><strong>Kasir:</strong> {{ $retur->user->name ?? 'N/A' }}</p>
        <p><strong>Alasan Retur:</strong> {{ $retur->reason }}</p>
      </div>
      <div class="col-md-6">
        <p><strong>Faktur Penjualan Terkait:</strong>
        @if ($retur->sale)
      <a href="{{ route('penjualan.retur.show', $retur) }}">{{ $retur->sale->invoice_number }}</a>
      (Pelanggan: {{ $retur->sale->customer_name ?? 'Umum' }})
      @else
      N/A (Retur Umum)
      @endif
        </p>
        <p><strong>Total Nilai Barang Diretur:</strong> Rp
        {{ number_format($retur->total_returned_amount, 0, ',', '.') }}
        </p>
        <p><strong>Jumlah Refund:</strong> Rp {{ number_format($retur->refund_amount ?? 0, 0, ',', '.') }}</p>
        <p><strong>Catatan:</strong> {{ $retur->notes ?? '-' }}</p>
      </div>
      </div>
    </div>
    </div>

    <div class="card shadow-sm mb-4">
    <div class="card-header bg-info text-white">
      Daftar Barang Diretur
    </div>
    <div class="card-body">
      <div class="table-responsive">
      <table class="table table-bordered">
        <thead>
        <tr>
          <th>Nama Barang</th>
          <th>Kuantitas Diretur</th>
          <th>Harga Unit</th>
          <th>Subtotal</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($retur->items as $item)
      <tr>
        <td>{{ $item->item->name ?? 'Barang Tidak Ditemukan' }}</td>
        <td>{{ $item->quantity }}</td>
        <td>Rp {{ number_format($item->price_per_unit, 0, ',', '.') }}</td>
        <td>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
      </tr>
      @endforeach
        </tbody>
      </table>
      </div>
    </div>
    </div>

    <div class="d-flex justify-content-between">
    <a href="{{ route('penjualan.retur.index') }}" class="btn btn-secondary">Kembali ke Daftar Retur</a>
    <form action="{{ route('penjualan.retur.destroy', $retur->id) }}" method="POST" class="d-inline"
      onsubmit="return confirm('Anda yakin ingin menghapus retur ini? Tindakan ini akan mengembalikan stok barang.');">
      @csrf
      @method('DELETE')
      <button type="submit" class="btn btn-danger">Hapus Retur</button>
    </form>
    </div>
  </div>
@endsection