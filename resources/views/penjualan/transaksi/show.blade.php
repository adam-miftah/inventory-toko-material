@extends('layouts.app')

@section('title', 'Detail Transaksi Penjualan')

@section('content')
  <div class="container-fluid">
    @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="fas fa-check-circle me-2"></i>
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h4 class=" mb-0 text-gray-800">Detail Transaksi [{{ $transaksi->invoice_number }}]</h4>
    <div>
      <a href="{{ route('penjualan.transaksi.index') }}" class="btn btn-secondary btn-sm">
      <i class="fas fa-arrow-left me-2"></i>Kembali
      </a>
      <a href="{{ route('penjualan.transaksi.print_receipt', $transaksi) }}" target="_blank"
      class="btn btn-success btn-sm">
      <i class="fas fa-print me-2"></i>Cetak Struk
      </a>
    </div>
    </div>

    <div class="row">
    <!-- Transaction Info -->
    <div class="col-lg-4 mb-5">
      <div class="card border-left-primary shadow h-100">
      <div class="card-header bg-primary text-white py-3">
        <h6 class="m-0 font-weight-bold">
        <i class="fas fa-info-circle me-2"></i>Informasi Transaksi
        </h6>
      </div>
      <div class="card-body">
        <div class="transaction-info">
        <div class="info-item d-flex mb-3">
          <div class="info-label flex-shrink-0 fw-bold" style="width: 120px;">Nomor Faktur</div>
          <div class="info-value">{{ $transaksi->invoice_number }}</div>
        </div>
        <div class="info-item d-flex mb-3">
          <div class="info-label flex-shrink-0 fw-bold" style="width: 120px;">Tanggal</div>
          <div class="info-value">
          @php
        $date = \Carbon\Carbon::parse($transaksi->transaksi_date)->timezone('Asia/Jakarta');
        $days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        echo $days[$date->dayOfWeek] . ', ' . $date->translatedFormat('d F Y H:i');
      @endphp
          </div>
        </div>
        <div class="info-item d-flex mb-3">
          <div class="info-label flex-shrink-0 fw-bold" style="width: 120px;">Pelanggan</div>
          <div class="info-value">{{ $transaksi->customer_name ?? 'Umum' }}</div>
        </div>
        <div class="info-item d-flex mb-3">
          <div class="info-label flex-shrink-0 fw-bold" style="width: 120px;">Kasir</div>
          <div class="info-value">{{ $transaksi->user->name ?? 'N/A' }}</div>
        </div>
        <div class="info-item d-flex mb-3">
          <div class="info-label flex-shrink-0 fw-bold" style="width: 120px;">Pembayaran</div>
          <div class="info-value text-capitalize">{{ $transaksi->payment_method }}</div>
        </div>
        <div class="info-item d-flex">
          <div class="info-label flex-shrink-0 fw-bold" style="width: 120px;">Catatan</div>
          <div class="info-value">{{ $transaksi->notes ?? '-' }}</div>
        </div>
        </div>
      </div>
      </div>
    </div>

    <!-- Items and Payment Summary -->
    <div class="col-lg-8 mb-4">
      <!-- Items List -->
      <div class="card shadow mb-4">
      <div class="card-header bg-info text-white py-3">
        <h6 class="m-0 font-weight-bold">
        <i class="fas fa-shopping-cart me-2"></i>Item Penjualan
        </h6>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
        <table class="table table-hover mb-0">
          <thead class="bg-light">
          <tr>
            <th class="text-nowrap">Nama Barang</th>
            <th class="text-center">Qty</th>
            <th class="text-end">Harga</th>
            <th class="text-end">Subtotal</th>
          </tr>
          </thead>
          <tbody>
          @foreach ($transaksi->items as $item)
        <tr>
        <td>{{ $item->item_name }}</td>
        <td class="text-center">{{ $item->quantity }}</td>
        <td class="text-end">{{ 'Rp ' . number_format($item->unit_price, 0, ',', '.') }}</td>
        <td class="text-end">{{ 'Rp ' . number_format($item->subtotal, 0, ',', '.') }}</td>
        </tr>
      @endforeach
          </tbody>
        </table>
        </div>
      </div>
      </div>

      <!-- Payment Summary -->
      <div class="card shadow">
      <div class="card-header bg-success text-white py-3">
        <h6 class="m-0 font-weight-bold">
        <i class="fas fa-receipt me-2"></i>Ringkasan Pembayaran
        </h6>
      </div>
      <div class="card-body">
        <div class="table-responsive">
        <table class="table mb-0">
          <tbody>
          <tr>
            <td class="fw-bold border-0">Subtotal:</td>
            <td class="text-end border-0">{{ 'Rp ' . number_format($transaksi->total_amount, 0, ',', '.') }}</td>
          </tr>
          <tr>
            <td class="fw-bold border-0">Diskon:</td>
            <td class="text-end border-0 text-danger">-
            {{ 'Rp ' . number_format($transaksi->discount_amount, 0, ',', '.') }}
            </td>
          </tr>
          <tr>
            <td class="fw-bold border-0">Pajak:</td>
            <td class="text-end border-0">{{ 'Rp ' . number_format($transaksi->tax_amount, 0, ',', '.') }}</td>
          </tr>
          <tr class="border-top">
            <td class="fw-bold">Grand Total:</td>
            <td class="text-end fw-bold fs-5">{{ 'Rp ' . number_format($transaksi->grand_total, 0, ',', '.') }}
            </td>
          </tr>
          <tr>
            <td class="fw-bold border-0">Dibayar:</td>
            <td class="text-end border-0 text-success">
            {{ 'Rp ' . number_format($transaksi->paid_amount, 0, ',', '.') }}
            </td>
          </tr>
          <tr>
            <td class="fw-bold border-0">Kembalian:</td>
            <td class="text-end border-0 fw-bold text-primary fs-5">
            {{ 'Rp ' . number_format($transaksi->change_amount, 0, ',', '.') }}
            </td>
          </tr>
          </tbody>
        </table>
        </div>
      </div>
      <div class="card-footer bg-light text-center">
        <small class="text-muted">Transaksi selesai pada
        {{ \Carbon\Carbon::parse($transaksi->created_at)->timezone('Asia/Jakarta')->translatedFormat('d F Y H:i:s') }}</small>
      </div>
      </div>
    </div>
    </div>
  </div>

  <style>
    .card {
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15) !important;
    font-size: 0.8rem;
    }


    .card-header {
    border-bottom: none;
    }

    .transaction-info .info-item {
    border-bottom: 1px dashed #eee;
    padding-bottom: 0.5rem;
    }

    .transaction-info .info-item:last-child {
    border-bottom: none;
    padding-bottom: 0;
    }

    .table th {
    border-top: none;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.75rem;
    letter-spacing: 0.5px;
    }

    .table td {
    font-size: 0.8rem;
    }

    @media (max-width: 768px) {
    .transaction-info .info-label {
      width: 100px !important;
    }
    }

    @media print {

    .btn,
    .d-sm-flex {
      display: none !important;
    }

    body {
      background: white !important;
      color: black !important;
    }

    .card {
      border: none !important;
      box-shadow: none !important;
    }
    }
  </style>
@endsection