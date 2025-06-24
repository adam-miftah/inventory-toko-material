@extends('layouts.app')

@section('title', 'Buat Retur Penjualan Baru')

@section('content')
  <div class="container-fluid">
    <h4 class="mb-4">Buat Retur Penjualan Baru</h4>

    @if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
    {{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
    @if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
    <ul>
      @foreach ($errors->all() as $error)
      <li>{{ $error }}</li>
    @endforeach
    </ul>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <form action="{{ route('penjualan.retur.store') }}" method="POST" id="sale-return-form">
    @csrf

    <div class="card shadow-sm mb-4">
      <div class="card-header bg-primary text-white">
      Informasi Retur
      </div>
      <div class="card-body">
      <div class="mb-3">
        <label for="sale_id" class="form-label">Nomor Faktur Penjualan (Opsional):</label>
        <select class="form-control select2" id="sale_id" name="sale_id">
        <option value="">-- Pilih Faktur Penjualan (jika retur terkait) --</option>
        @foreach ($sales as $sale)
        <option value="{{ $sale->id }}"
          data-sale-details="{{ json_encode($sale->items->map(function ($item) {
      return ['id' => $item->item_id, 'name' => $item->item_name, 'quantity' => $item->quantity, 'price_per_unit' => $item->unit_price, 'original_stock' => $item->item->stock]; })) }}"
          {{ old('sale_id') == $sale->id ? 'selected' : '' }}>
          {{ $sale->invoice_number }} (Pelanggan: {{ $sale->customer_name ?? 'Umum' }}, Total: Rp
          {{ number_format($sale->grand_total, 0, ',', '.') }})
        </option>
      @endforeach
        </select>
        <small class="form-text text-muted">Memilih faktur akan mengisi daftar barang yang terjual pada faktur
        tersebut.</small>
      </div>

      <div class="mb-3">
        <label for="return_date" class="form-label">Tanggal Retur:</label>
        <input type="date" class="form-control" id="return_date" name="return_date"
        value="{{ old('return_date', date('Y-m-d')) }}" required>
      </div>

      <div class="mb-3">
        <label for="reason" class="form-label">Alasan Retur:</label>
        <textarea class="form-control" id="reason" name="reason" rows="3" required>{{ old('reason') }}</textarea>
      </div>

      <div class="mb-3">
        <label for="notes" class="form-label">Catatan Tambahan (Opsional):</label>
        <textarea class="form-control" id="notes" name="notes" rows="2">{{ old('notes') }}</textarea>
      </div>
      </div>
    </div>

    <div class="card shadow-sm mb-4">
      <div class="card-header bg-info text-white">
      Daftar Barang Diretur
      </div>
      <div class="card-body">
      <div class="mb-3">
        <label for="item_select" class="form-label">Pilih Barang (Jika retur tidak terkait faktur atau ada barang
        tambahan):</label>
        <select class="form-control select2" id="item_select">
        <option value="">-- Cari atau Pilih Barang --</option>
        @foreach (App\Models\Item::all() as $item)
      <option value="{{ $item->id }}" data-name="{{ $item->name }}" data-price="{{ $item->price }}"
        data-stock="{{ $item->stock }}">
        {{ $item->name }} (Stok: {{ $item->stock }} {{ $item->unit }})
      </option>
      @endforeach
        </select>
      </div>
      <div class="table-responsive">
        <table class="table table-bordered" id="returned-items-table">
        <thead>
          <tr>
          <th>Nama Barang</th>
          <th>Harga Unit Saat Ini</th>
          <th>Kuantitas Diretur</th>
          <th>Subtotal</th>
          <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          {{-- Item yang diretur akan ditambahkan di sini oleh JavaScript --}}
        </tbody>
        </table>
      </div>
      </div>
    </div>

    <div class="card shadow-sm mb-4">
      <div class="card-header bg-success text-white">
      Ringkasan Refund
      </div>
      <div class="card-body">
      <div class="row mb-3">
        <div class="col-md-6">
        <label for="total_returned_amount" class="form-label">Total Nilai Barang Diretur:</label>
        <input type="text" class="form-control fw-bold fs-5" id="total_returned_amount" value="Rp 0" readonly>
        <input type="hidden" name="total_returned_amount_raw" id="total_returned_amount_raw" value="0">
        </div>
        <div class="col-md-6">
        <label for="refund_amount" class="form-label">Jumlah Uang yang Dikembalikan (Refund):</label>
        <input type="number" class="form-control" id="refund_amount" name="refund_amount"
          value="{{ old('refund_amount', 0) }}" min="0" step="1">
        </div>
      </div>
      </div>
    </div>

    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
      <button type="submit" class="btn btn-success w-25" id="process-return-btn">Proses Retur</button>
      <a href="{{ route('penjualan.retur.index') }}" class="btn btn-outline-secondary btn-sm w-25">Batal</a>
    </div>
    </form>
  </div>
@endsection

@push('styles')
  <style>
    .select2-container .select2-selection--single {
    height: calc(1.5em + .75rem + 2px);
    border-color: #ced4da;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
    line-height: calc(1.5em + .75rem);
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
    height: calc(1.5em + .75rem);
    }
  </style>
@endpush

@push('scripts')
  <script>
    $(document).ready(function () {
    $('.select2').select2({
      placeholder: "-- Pilih --",
      allowClear: true
    });

    var returnedItems = {};
    function updateReturnedItemsTable() {
      var totalAmount = 0;
      var $tableBody = $('#returned-items-table tbody');
      $tableBody.empty();

      for (var itemId in returnedItems) {
      var item = returnedItems[itemId];
      var subtotal = item.quantity_to_return * item.price_per_unit;
      totalAmount += subtotal;

      var maxQuantity = item.sale_item_quantity !== undefined ? item.sale_item_quantity : item.original_stock; // Max quantity based on original sale or current stock

      var row = `
      <tr>
      <td>
      ${item.name}
      <input type="hidden" name="returned_items[${itemId}][item_id]" value="${itemId}">
      </td>
      <td>Rp ${numberFormat(item.price_per_unit)}</td>
      <td>
      <input type="number" class="form-control form-control-sm quantity-input"
      name="returned_items[${itemId}][quantity]"
      value="${item.quantity_to_return}"
      min="1"
      ${maxQuantity !== undefined ? `max="${maxQuantity}"` : ''}
      data-item-id="${itemId}"
      data-original-price="${item.price_per_unit}">
      ${maxQuantity !== undefined ? `<small class="text-muted">Max: ${maxQuantity}</small>` : ''}
      </td>
      <td class="item-subtotal">Rp ${numberFormat(subtotal)}</td>
      <td>
      <button type="button" class="btn btn-danger btn-sm remove-item" data-item-id="${itemId}">Hapus</button>
      </td>
      </tr>
      `;
      $tableBody.append(row);
      }

      $('#total_returned_amount_raw').val(totalAmount);
      $('#total_returned_amount').val('Rp ' + numberFormat(totalAmount));

      // Atur nilai default untuk refund_amount jika kosong atau 0
      if (parseFloat($('#refund_amount').val()) === 0 || $('#refund_amount').val() === '') {
      $('#refund_amount').val(totalAmount);
      }

      // Validasi tombol proses
      if (Object.keys(returnedItems).length > 0) {
      $('#process-return-btn').prop('disabled', false);
      } else {
      $('#process-return-btn').prop('disabled', true);
      }
    }

    // Event listener saat memilih faktur penjualan
    $('#sale_id').change(function () {
      var $selectedOption = $(this).find('option:selected');
      var saleDetails = $selectedOption.data('sale-details');

      returnedItems = {}; // Reset item yang diretur
      if (saleDetails) {
      saleDetails.forEach(function (saleItem) {
        returnedItems[saleItem.id] = {
        name: saleItem.name,
        price_per_unit: saleItem.price_per_unit,
        quantity_to_return: saleItem.quantity, // Default: retur semua yang terjual
        sale_item_quantity: saleItem.quantity, // Kuantitas asli di faktur
        original_stock: saleItem.original_stock // Stok barang saat ini (bukan saat dijual)
        };
      });
      }
      updateReturnedItemsTable();
    });

    // Event listener saat memilih barang dari dropdown (untuk retur non-faktur atau tambahan)
    $('#item_select').change(function () {
      var $selectedOption = $(this).find('option:selected');
      var itemId = $selectedOption.val();
      var itemName = $selectedOption.data('name');
      var itemPrice = $selectedOption.data('price');
      var itemStock = $selectedOption.data('stock'); // Ini adalah stock di DB

      if (itemId && !returnedItems[itemId]) {
      returnedItems[itemId] = {
        name: itemName,
        price_per_unit: itemPrice,
        quantity_to_return: 1, // Default kuantitas 1
        sale_item_quantity: undefined, // Tidak terkait dengan penjualan asli
        original_stock: itemStock // Ini adalah stok saat ini untuk barang umum
      };
      updateReturnedItemsTable();
      $(this).val('').trigger('change'); // Reset Select2
      } else if (itemId && returnedItems[itemId]) {
      // Jika item sudah ada, tingkatkan kuantitasnya
      if (returnedItems[itemId].sale_item_quantity !== undefined) {
        // Jika dari faktur, jangan biarkan melebihi kuantitas asli di faktur
        if (returnedItems[itemId].quantity_to_return < returnedItems[itemId].sale_item_quantity) {
        returnedItems[itemId].quantity_to_return++;
        updateReturnedItemsTable();
        } else {
        alert('Kuantitas retur untuk barang ini sudah mencapai batas kuantitas yang terjual di faktur.');
        }
      } else {
        // Jika barang umum (tidak dari faktur), tidak ada batas atas spesifik selain logika bisnis Anda
        returnedItems[itemId].quantity_to_return++;
        updateReturnedItemsTable();
      }
      $(this).val('').trigger('change'); // Reset Select2
      }
    });


    // Event listener untuk perubahan kuantitas pada tabel item yang diretur
    $('#returned-items-table').on('input', '.quantity-input', function () {
      var itemId = $(this).data('item-id');
      var newQuantity = parseInt($(this).val()) || 0;
      var maxQuantity = parseInt($(this).attr('max')); // Ini akan ada jika dari faktur

      if (maxQuantity !== undefined && newQuantity > maxQuantity) {
      newQuantity = maxQuantity;
      $(this).val(newQuantity);
      alert('Kuantitas retur tidak boleh melebihi kuantitas yang terjual di faktur ini.');
      } else if (newQuantity < 1 && newQuantity !== 0) {
      newQuantity = 1;
      $(this).val(newQuantity);
      }

      if (returnedItems[itemId]) {
      returnedItems[itemId].quantity_to_return = newQuantity;
      var subtotal = returnedItems[itemId].quantity_to_return * returnedItems[itemId].price_per_unit;
      $(this).closest('tr').find('.item-subtotal').text('Rp ' + numberFormat(subtotal));
      updateReturnedItemsTable();
      }
    });

    // Event listener untuk menghapus item dari tabel
    $('#returned-items-table').on('click', '.remove-item', function () {
      var itemId = $(this).data('item-id');
      delete returnedItems[itemId];
      updateReturnedItemsTable();
    });

    // Event listener untuk perubahan jumlah refund
    $('#refund_amount').on('input', function () {
      // Pastikan nilai tidak negatif
      if (parseFloat($(this).val()) < 0) {
      $(this).val(0);
      }
    });


    // Fungsi pembantu untuk format angka ke Rupiah
    function numberFormat(amount) {
      return new Intl.NumberFormat('id-ID').format(amount);
    }

    // Fungsi pembantu untuk menghapus format angka (misal: "Rp 1.000.000" menjadi 1000000)
    function removeNumberFormat(formattedAmount) {
      return formattedAmount.replace(/[^0-9,-]+/g, '').replace(/,/g, '.');
    }

    // Inisialisasi awal (nonaktifkan tombol proses retur jika belum ada item)
    $('#process-return-btn').prop('disabled', true);
    });
  </script>
@endpush