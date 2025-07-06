<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cetak Laporan Laba Rugi</title>
  <style>
    /* Gaya Profesional untuk Laporan Keuangan */
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap');

    body {
      font-family: 'Poppins', sans-serif;
      font-size: 10pt;
      color: #333;
    }

    @page {
      margin: 120px 40px 80px 40px;
    }

    header {
      position: fixed;
      top: -90px;
      left: 0px;
      right: 0px;
      height: 150px;
      width: 100%;
    }

    footer {
      position: fixed;
      bottom: -60px;
      left: 0px;
      right: 0px;
      height: 50px;
      font-size: 8pt;
      color: #777;
    }

    .footer-content {
      border-top: 1px solid #e0e0e0;
      padding-top: 10px;
    }

    .page-number:before {
      content: "Halaman " counter(page);
    }

    .container {
      width: 100%;
    }

    .brand-header {
      display: block;
      margin-bottom: 20px;
      border-bottom: 3px solid #0D47A1;
      padding-bottom: 15px;
    }

    .brand-logo {
      float: left;
      width: 80px;
      height: 80px;
    }

    .brand-logo img {
      width: 100%;
    }

    .company-details {
      float: right;
      text-align: right;
    }

    .company-details h2 {
      margin: 0;
      font-size: 16pt;
      font-weight: 700;
      color: #0D47A1;
    }

    .company-details p {
      margin: 0;
      font-size: 9pt;
      color: #555;
    }

    .profit-loss-table {
      width: 100%;
      border-collapse: collapse;
    }

    .profit-loss-table th,
    .profit-loss-table td {
      padding: 10px;
      text-align: left;
      border-bottom: 1px solid #eee;
    }

    .profit-loss-table th {
      background-color: #f8f9fa;
      font-size: 11pt;
      color: #333;
      border-bottom: 2px solid #ddd;
    }

    .pl-header {
      font-size: 11pt;
      font-weight: 600;
      color: #0D47A1;
      padding-top: 20px;
    }

    .pl-item {
      padding-left: 25px;
    }

    .pl-subtotal {
      font-weight: 600;
      border-top: 1px solid #ccc;
    }

    .pl-grand-total {
      background-color: #f2f2f2;
      font-weight: 700;
      font-size: 12pt;
      border-top: 2px solid #333;
      border-bottom: 3px double #333;
    }

    .text-right {
      text-align: right;
    }

    .text-success {
      color: #28a745;
    }

    .text-danger {
      color: #d9534f;
    }

    .clearfix::after {
      content: "";
      clear: both;
      display: table;
    }
  </style>
</head>

<body>
  <header class="clearfix">
    <div class="brand-header">
      <div class="brand-logo"><img src="https://example.com/logo.png" alt="Logo"></div>
      <div class="company-details">
        <h2>Laporan Laba Rugi</h2>
        <p><strong>NAMA TOKO / PERUSAHAAN ANDA</strong></p>
        @php
      $periode = \Carbon\Carbon::createFromDate($filterYear, $filterMonth, 1);
    @endphp
        <p>Untuk Periode yang Berakhir pada: <strong>{{ $periode->endOfMonth()->isoFormat('DD MMMM YYYY') }}</strong>
        </p>
      </div>
    </div>
  </header>

  <footer>
    <div class="footer-content clearfix">
      <div style="float: left;">Laporan ini dibuat oleh sistem secara otomatis.</div>
      <div style="float: right;" class="page-number"></div>
    </div>
  </footer>

  <main class="container">
    <table class="profit-loss-table">
      <thead>
        <tr>
          <th>Deskripsi</th>
          <th class="text-right">Material</th>
          <th class="text-right">Keramik</th>
          <th class="text-right">Total</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td colspan="4" class="pl-header">Pendapatan dari Penjualan</td>
        </tr>
        <tr>
          <td class="pl-item">Pendapatan Bersih</td>
          <td class="text-right">Rp {{ number_format($penjualanBersihMaterial, 0, ',', '.') }}</td>
          <td class="text-right">Rp {{ number_format($penjualanBersihKeramik, 0, ',', '.') }}</td>
          <td class="text-right">Rp {{ number_format($totalPendapatanBersih, 0, ',', '.') }}</td>
        </tr>
        <tr>
          <td colspan="4" class="pl-header">Harga Pokok Penjualan (HPP)</td>
        </tr>
        <tr>
          <td class="pl-item">HPP Bersih</td>
          <td class="text-right">Rp {{ number_format($pembelianBersihMaterial, 0, ',', '.') }}</td>
          <td class="text-right">Rp {{ number_format($pembelianBersihKeramik, 0, ',', '.') }}</td>
          <td class="text-right">Rp {{ number_format($totalHpp, 0, ',', '.') }}</td>
        </tr>
        <tr class="pl-grand-total">
          <td>LABA KOTOR</td>
          <td class="text-right {{ $labaKotorMaterial >= 0 ? 'text-success' : 'text-danger' }}">Rp
            {{ number_format($labaKotorMaterial, 0, ',', '.') }}
          </td>
          <td class="text-right {{ $labaKotorKeramik >= 0 ? 'text-success' : 'text-danger' }}">Rp
            {{ number_format($labaKotorKeramik, 0, ',', '.') }}
          </td>
          <td class="text-right {{ $totalLabaKotor >= 0 ? 'text-success' : 'text-danger' }}">Rp
            {{ number_format($totalLabaKotor, 0, ',', '.') }}
          </td>
        </tr>
      </tbody>
    </table>
    <p style="margin-top: 30px; font-size: 8pt; color: #777; text-align: center;">* Laba Kotor dihitung dari Pendapatan
      Bersih dikurangi Harga Pokok Penjualan (HPP). Biaya operasional lain tidak termasuk.</p>
  </main>
</body>

</html>