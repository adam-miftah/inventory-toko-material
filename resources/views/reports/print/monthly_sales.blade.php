<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cetak Laporan Penjualan Bulanan</title>
  <style>
    body {
      font-family: sans-serif;
      font-size: 10pt;
    }

    h1,
    h2,
    h3 {
      text-align: center;
    }

    .container {
      width: 80%;
      margin: 0 auto;
    }

    .table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }

    .table th,
    .table td {
      border: 1px solid #ddd;
      padding: 8px;
      text-align: left;
    }

    .table th {
      background-color: #f2f2f2;
    }

    .text-right {
      text-align: right;
    }

    .fw-bold {
      font-weight: bold;
    }
  </style>
</head>

<body>
  <div class="container">
    <h1>Laporan Penjualan Bulanan per Kategori</h1>
    <h2>Periode: {{ date('F', mktime(0, 0, 0, $filterMonth ?? now()->month, 1)) }} {{ $filterYear ?? now()->year }}</h2>

    <table class="table">
      <thead>
        <tr>
          <th>Kategori</th>
          <th>Total Penjualan</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>Material (Cat)</td>
          <td>Rp {{ number_format($monthlySalesMaterial ?? 0, 0, ',', '.') }}</td>
        </tr>
        <tr>
          <td>Keramik</td>
          <td>Rp {{ number_format($monthlySalesKeramik ?? 0, 0, ',', '.') }}</td>
        </tr>
      </tbody>
      <tfoot>
        <tr>
          <th class="text-right">Total Penjualan Keseluruhan</th>
          <th class="fw-bold">Rp {{ number_format($totalMonthlySales ?? 0, 0, ',', '.') }}</th>
        </tr>
      </tfoot>
    </table>

    <h3>Detail Transaksi Penjualan</h3>
    @if ($salesThisMonth && $salesThisMonth->count() > 0)
    <table class="table">
      <thead>
      <tr>
        <th>No</th>
        <th>Invoice</th>
        <th>Tanggal</th>
        <th>Pelanggan</th>
        <th>Kategori</th>
        <th>Total</th>
      </tr>
      </thead>
      <tbody>
      @foreach ($salesThisMonth as $key => $sale)
      @foreach ($sale->items as $saleItem)
      <tr>
      <td>{{ $loop->parent->iteration }}.{{ $loop->iteration }}</td>
      <td>{{ $sale->invoice_number }}</td>
      <td>{{ $sale->sale_date->format('d-m-Y H:i') }}</td>
      <td>{{ $sale->customer_name ?? '-' }}</td>
      <td>{{ $saleItem->item->category->name ?? '-' }}</td>
      <td>Rp {{ number_format($saleItem->quantity * $saleItem->unit_price, 0, ',', '.') }}</td>
      </tr>
      @endforeach
      <tr class="fw-bold">
        <td colspan="5" class="text-right">Total Transaksi {{ $sale->invoice_number }}:</td>
        <td>Rp {{ number_format($sale->grand_total, 0, ',', '.') }}</td>
      </tr>
    @endforeach
      </tbody>
    </table>
  @else
    <p>Tidak ada transaksi penjualan pada bulan {{ date('F', mktime(0, 0, 0, $filterMonth ?? now()->month, 1)) }}
      {{ $filterYear ?? now()->year }}.
    </p>
  @endif
  </div>
</body>

</html>