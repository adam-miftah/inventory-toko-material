<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Struk Penjualan #{{ $sale->invoice_number }}</title>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
  <style>
    /* Corporate Style */
    :root {
      --primary-color: #2c3e50;
      --secondary-color: #3498db;
      --accent-color: #e74c3c;
      --light-gray: #f5f7fa;
      --medium-gray: #e0e0e0;
      --dark-gray: #7f8c8d;
    }

    body {
      font-family: 'Roboto', sans-serif;
      margin: 0;
      padding: 0;
      background-color: var(--light-gray);
      color: #333;
      line-height: 1.6;
    }

    .container {
      max-width: 80mm;
      margin: 20px auto;
      background-color: #fff;
      padding: 0;
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
      border-radius: 8px;
      overflow: hidden;
    }

    .header {
      background-color: white;
      color: var(--primary-color);
      padding: 20px;
      text-align: center;
      position: relative;
    }

    /* 
    .header:after {
      content: "";
      position: absolute;
      bottom: -10px;
      left: 0;
      right: 0;
      height: 20px;
      background: linear-gradient(to bottom right, transparent 49%, var(--primary-color) 50%),
        linear-gradient(to bottom left, transparent 49%, var(--primary-color) 50%);
      background-size: 20px 20px;
      background-repeat: repeat-x;
    } */

    .header p {
      margin: 3px 0;
      font-size: 0.75rem;
      opacity: 0.9;
    }

    .logo {
      max-width: 80px;
      margin-bottom: 10px;
    }

    .invoice-title {
      background-color: var(--secondary-color);
      color: white;
      padding: 10px 20px;
      text-align: center;
      font-weight: 500;
      font-size: 1.1rem;
      margin-bottom: 15px;
    }

    .info-section {
      padding: 0 20px;
      margin-bottom: 15px;
    }

    .info-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 10px;
      font-size: 0.8rem;
    }

    .info-item {
      margin-bottom: 5px;
    }

    .info-label {
      font-weight: 500;
      color: var(--dark-gray);
      display: block;
    }

    .info-value {
      font-weight: 400;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin: 15px 0;
      font-size: 0.75rem;
    }

    table th {
      background-color: var(--light-gray);
      color: var(--primary-color);
      font-weight: 500;
      padding: 8px 10px;
      text-align: left;
      border-bottom: 2px solid var(--medium-gray);
    }

    table td {
      padding: 8px 10px;
      border-bottom: 1px solid var(--medium-gray);
    }

    table tr:last-child td {
      border-bottom: none;
    }

    .summary {
      padding: 0 20px;
      margin: 20px 0;
    }

    .summary-item {
      display: flex;
      justify-content: space-between;
      margin-bottom: 5px;
      font-size: 0.8rem;
    }

    .summary-label {
      font-weight: 500;
      color: var(--dark-gray);
    }

    .grand-total {
      font-size: 1rem;
      font-weight: 700;
      color: var(--accent-color);
      margin-top: 10px;
      padding-top: 10px;
      border-top: 2px dashed var(--medium-gray);
    }

    .payment-method {
      background-color: var(--light-gray);
      padding: 10px;
      border-radius: 5px;
      margin-top: 15px;
      font-size: 0.8rem;
    }

    .payment-method span {
      font-weight: 500;
      color: var(--primary-color);
    }

    .note {
      background-color: #fff8e1;
      padding: 10px 20px;
      margin: 20px 0;
      font-size: 0.7rem;
      border-left: 4px solid #ffc107;
    }

    .footer {
      text-align: center;
      padding: 20px;
      background-color: var(--light-gray);
      font-size: 0.7rem;
      color: var(--dark-gray);
    }

    .thank-you {
      font-weight: 500;
      color: var(--primary-color);
      margin-bottom: 5px;
    }

    .barcode {
      margin: 15px auto;
      text-align: center;
    }

    .barcode img {
      max-width: 150px;
      height: auto;
    }

    .action-buttons {
      text-align: center;
      margin: 30px auto;
      max-width: 80mm;
    }

    .action-buttons button,
    .action-buttons a {
      padding: 12px 25px;
      margin: 5px;
      border: none;
      border-radius: 30px;
      cursor: pointer;
      text-decoration: none;
      display: inline-block;
      font-weight: 500;
      transition: all 0.3s;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .action-buttons .print-btn {
      background-color: var(--primary-color);
      color: white;
    }

    .action-buttons .print-btn:hover {
      background-color: #1a252f;
    }

    .action-buttons .back-btn {
      background-color: white;
      color: var(--primary-color);
      border: 1px solid var(--primary-color);
    }

    .action-buttons .back-btn:hover {
      background-color: var(--light-gray);
    }

    /* Gaya khusus untuk pencetakan */
    @media print {
      body {
        background: none;
        margin: 0;
        padding: 0;
        font-size: 10pt;
      }

      .container {
        width: 80mm;
        max-width: 80mm;
        margin: 0;
        padding: 0;
        box-shadow: none;
        border-radius: 0;
      }

      .action-buttons {
        display: none;
      }

      @page {
        size: auto;
        margin: 0;
      }
    }

    /* Garis putus-putus untuk bagian bawah struk */
    .cut-line {
      text-align: center;
      margin: 10px 0;
      position: relative;
      color: var(--dark-gray);
      font-size: 0.7rem;
    }

    .cut-line:before,
    .cut-line:after {
      content: "";
      position: absolute;
      top: 50%;
      width: 30%;
      height: 1px;
      background: repeating-linear-gradient(to right, var(--dark-gray) 0px, var(--dark-gray) 5px, transparent 5px, transparent 10px);
    }

    .cut-line:before {
      left: 0;
    }

    .cut-line:after {
      right: 0;
    }
  </style>
</head>

<body>
  <div class="container">
    <div class="header">
      <!-- Ganti dengan logo perusahaan Anda -->
      <img src="{{ asset('images/newstruk.png') }}" alt="Company Logo" class="logo" style="margin-bottom: -40px;">
      <h4 style="margin-bottom: 0;">TB SOGOL ANUGRAH MANDIRI</h4>
      <p>Jl. Contoh No.123, Kota Anda</p>
      <p>Telp: (021) 12345678 | Email: info@perusahaan.com</p>
    </div>
    <hr>
    <div class="info-section">
      <div class="info-grid">
        <div class="info-item">
          <span class="info-label">No. Invoice</span>
          <span class="info-value">{{ $sale->invoice_number }}</span>
        </div>
        <div class="info-item">
          <span class="info-label">Tanggal</span>
          <span class="info-value">{{ now()->timezone('Asia/Jakarta')->format('d M Y H:i') }}</span>
        </div>

      </div>
    </div>
    <hr>
    <div style="padding: 0 20px;">
      <table>
        <thead>
          <tr>
            <th>#</th>
            <th>Item</th>
            <th>Qty</th>
            <th style="text-align: right;">Harga</th>
            <th style="text-align: right;">Subtotal</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($sale->items as $index => $item)
        <tr>
        <td>{{ $index + 1 }}</td>
        <td>{{ $item->item_name }}</td>
        <td>{{ $item->quantity }}</td>
        <td style="text-align: right;">Rp {{ number_format($item->unit_price, 0, ',', '.') }}</td>
        <td style="text-align: right;">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
        </tr>
      @endforeach
        </tbody>
      </table>
    </div>

    <div class="summary">
      <div class="summary-item">
        <span class="summary-label">Subtotal</span>
        <span>Rp {{ number_format($sale->total_amount, 0, ',', '.') }}</span>
      </div>
      <div class="summary-item">
        <span class="summary-label">Diskon</span>
        <span>- Rp {{ number_format($sale->discount_amount, 0, ',', '.') }}</span>
      </div>
      <div class="summary-item">
        <span class="summary-label">Pajak</span>
        <span>Rp {{ number_format($sale->tax_amount, 0, ',', '.') }}</span>
      </div>
      <div class="summary-item grand-total">
        <span class="summary-label">TOTAL</span>
        <span>Rp {{ number_format($sale->grand_total, 0, ',', '.') }}</span>
      </div>

      <div class="payment-method">
        <div class="summary-item">
          <span class="summary-label">Metode Pembayaran</span>
          <span>{{ ucfirst($sale->payment_method) }}</span>
        </div>
        <div class="summary-item">
          <span class="summary-label">Dibayar</span>
          <span>Rp {{ number_format($sale->paid_amount, 0, ',', '.') }}</span>
        </div>
        <div class="summary-item">
          <span class="summary-label">Kembalian</span>
          <span>Rp {{ number_format($sale->change_amount, 0, ',', '.') }}</span>
        </div>
      </div>
    </div>

    @if ($sale->notes)
    <div class="note">
      <strong>Catatan:</strong> {{ $sale->notes }}
    </div>
  @endif

    <div class="barcode">
      <!-- Anda bisa menambahkan barcode generator untuk nomor invoice -->
      <img src="https://barcode.tec-it.com/barcode.ashx?data={{ $sale->invoice_number }}&code=Code128&dpi=96"
        alt="Barcode">
    </div>

    <div class="cut-line">-- -- -- -- -- -- -- -- -- -- -- -- --</div>

    <div class="footer">
      <div class="thank-you">Terima kasih telah berbelanja!</div>
      <p>Barang yang sudah dibeli tidak dapat ditukar/dikembalikan</p>
      <p>~ TB. SOGOL ANUGRAH MANDIRI ~</p>
    </div>
  </div>

  <div class="action-buttons">
    <button class="print-btn" onclick="window.print()">Cetak Struk</button>
    <a href="{{ route('penjualan.transaksi.index') }}" class="back-btn">Kembali ke Transaksi</a>
  </div>

  <script>
    // Otomatis memicu dialog cetak saat halaman dimuat
    window.onload = function () {
      // Uncomment baris berikut untuk auto-print
      // window.print();

      // Opsional: Tutup tab setelah dicetak (hanya bekerja di beberapa browser)
      window.onafterprint = function () {
        // window.close();
      };
    };
  </script>
</body>

</html>