<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cetak Laporan Laba Rugi</title>
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
      width: 60%;
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

    .text-success {
      color: green;
    }

    .text-danger {
      color: red;
    }
  </style>
</head>

<body>
  <div class="container">
    <h1>Laporan Laba Rugi per Kategori</h1>
    <h2>Periode: {{ date('F', mktime(0, 0, 0, $filterMonth ?? now()->month, 1)) }} {{ $filterYear ?? now()->year }}</h2>

    <table class="table">
      <thead>
        <tr>
          <th>Kategori</th>
          <th>Total Penjualan</th>
          <th>Total Pembelian</th>
          <th>Laba Rugi</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>Material (Cat)</td>
          <td>Rp {{ number_format($penjualanMaterial ?? 0, 0, ',', '.') }}</td>
          <td>Rp {{ number_format($pembelianMaterial ?? 0, 0, ',', '.') }}</td>
          <td class="{{ ($labaRugiMaterial ?? 0) >= 0 ? 'text-success' : 'text-danger' }}">
            Rp {{ number_format($labaRugiMaterial ?? 0, 0, ',', '.') }}
          </td>
        </tr>
        <tr>
          <td>Keramik</td>
          <td>Rp {{ number_format($penjualanKeramik ?? 0, 0, ',', '.') }}</td>
          <td>Rp {{ number_format($pembelianKeramik ?? 0, 0, ',', '.') }}</td>
          <td class="{{ ($labaRugiKeramik ?? 0) >= 0 ? 'text-success' : 'text-danger' }}">
            Rp {{ number_format($labaRugiKeramik ?? 0, 0, ',', '.') }}
          </td>
        </tr>
      </tbody>
      <tfoot>
        <tr>
          <th colspan="3" class="text-right">Total Laba Rugi Keseluruhan</th>
          <th class="fw-bold {{ ($totalLabaRugi ?? 0) >= 0 ? 'text-success' : 'text-danger' }}">
            Rp {{ number_format($totalLabaRugi ?? 0, 0, ',', '.') }}
          </th>
        </tr>
      </tfoot>
    </table>

    <p class="text-center small">
      * Perhitungan ini adalah perkiraan sederhana berdasarkan total penjualan dan total pembelian per kategori.
      Biaya operasional, retur, dan faktor lain tidak termasuk dalam perhitungan ini.
    </p>
  </div>
</body>

</html>