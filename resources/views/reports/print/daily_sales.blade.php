<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cetak Laporan Penjualan Harian</title>
  <style>
    body {
      font-family: 'Helvetica Neue', 'Helvetica', Arial, sans-serif;
      font-size: 9pt;
      /* Ukuran font dikecilkan sedikit untuk memuat kolom baru */
      color: #333;
    }

    .container {
      width: 100%;
      margin: 0 auto;
    }

    .header {
      text-align: center;
      margin-bottom: 25px;
    }

    .header h1 {
      margin: 0;
      font-size: 18pt;
    }

    .header p {
      margin: 5px 0;
      font-size: 12pt;
    }

    .summary-table,
    .details-table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 25px;
    }

    .summary-table td,
    .details-table th,
    .details-table td {
      border: 1px solid #ddd;
      padding: 8px;
      /* Menambahkan word-break agar teks panjang tidak merusak layout */
      word-break: break-word;
    }

    .details-table th {
      background-color: #f2f2f2;
      font-weight: bold;
      text-align: left;
      font-size: 8pt;
      /* Dikecilkan agar pas */
      text-transform: uppercase;
    }

    .summary-table td:first-child {
      font-weight: bold;
      width: 70%;
    }

    .text-right {
      text-align: right;
    }

    .text-center {
      text-align: center;
    }

    .fw-bold {
      font-weight: bold;
    }

    .total-row {
      background-color: #f2f2f2;
      font-weight: bold;
    }

    .section-title {
      font-size: 14pt;
      font-weight: bold;
      margin-top: 30px;
      margin-bottom: 15px;
      border-bottom: 2px solid #333;
      padding-bottom: 5px;
    }

    .no-data {
      text-align: center;
      padding: 40px;
      font-style: italic;
      color: #777;
    }
  </style>
</head>

<body>
  <div class="container">
    <div class="header">
      <h1>Laporan Penjualan Harian</h1>
      <p>Tanggal: {{ \Carbon\Carbon::parse($filterDate ?? today())->isoFormat('DD MMMM YYYY') }}</p>
    </div>

    <div class="section-title">Ringkasan Keuangan</div>
    <table class="summary-table">
      <tr>
        <td>Penjualan Bersih Material</td>
        <td class="text-right fw-bold">Rp {{ number_format($netSalesMaterial ?? 0, 0, ',', '.') }}</td>
      </tr>
      <tr>
        <td>Penjualan Bersih Keramik</td>
        <td class="text-right fw-bold">Rp {{ number_format($netSalesKeramik ?? 0, 0, ',', '.') }}</td>
      </tr>
      <tr>
        <td>Total Retur Hari Ini</td>
        <td class="text-right fw-bold">Rp {{ number_format($totalDailyReturns ?? 0, 0, ',', '.') }}</td>
      </tr>
      <tr class="total-row">
        <td>TOTAL PENJUALAN BERSIH (KESELURUHAN)</td>
        <td class="text-right fw-bold">Rp {{ number_format($totalNetDailySales ?? 0, 0, ',', '.') }}</td>
      </tr>
    </table>

    <div class="section-title">Detail Transaksi</div>
    @if ($salesToday && $salesToday->count() > 0)
    <table class="details-table">
      <thead>
      <tr>
        <th class="text-center" style="width: 5%;">No</th>
        <th>Invoice</th>
        <th style="width: 10%;">Waktu</th>
        <th class="text-center">pelanggan</th>
        <th>Kasir</th>
        <th style=" width: 30%;">Nama Item</th>
        <th class="text-center" style="width: 10%;">Jml Item</th>
        <th class="text-right">Total Transaksi</th>
      </tr>
      </thead>
      <tbody>
      @foreach ($salesToday as $sale)
      <tr>
      <td class="text-center">{{ $loop->iteration }}</td>
      <td class="fw-bold">{{ $sale->invoice_number }}</td>
      <td>{{ $sale->sale_date->format('H:i') }}</td>
      <td>{{ $sale->customer_name ?? 'Umum' }}</td>
      <td>{{ $sale->user->name }}</td>
      {{-- PERUBAHAN 2: Menambahkan Sel untuk Nama Item --}}
      <td>
      {{-- Kode ini mengambil nama dari setiap item dalam transaksi, lalu menggabungkannya dengan koma --}}
      {{ $sale->items->pluck('item.name')->join(', ') }}
      </td>
      <td class="text-center">{{ $sale->items->sum('quantity') }}</td>
      <td class="text-right fw-bold">Rp {{ number_format($sale->grand_total, 0, ',', '.') }}</td>
      </tr>
    @endforeach
      </tbody>
    </table>
  @else
    <div class="no-data">
      <p>Tidak ada transaksi penjualan pada tanggal ini.</p>
    </div>
  @endif
  </div>
</body>

</html>