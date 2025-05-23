@extends('layouts.app')

@section('title', 'Transaksi Penjualan Baru')

@section('content')
  <div class="container-fluid py-0">
    <!-- Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <div>
      <h4 class=" mb-0 text-gray-800">Transaksi Penjualan Baru</h4>
      <p class="mb-0 text-muted" style="font-size: 0.8rem">Buat transaksi penjualan baru untuk pelanggan</p>
    </div>
    <div>
      <a href="{{ route('penjualan.transaksi.index') }}" class="btn btn-outline-secondary btn-sm">
      <i class="fas fa-arrow-left me-2"></i> Kembali
      </a>
    </div>
    </div>

    <!-- Alerts -->
    @if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
    <div class="d-flex align-items-center">
      <i class="fas fa-exclamation-circle me-2"></i>
      <div>{{ session('error') }}</div>
    </div>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
    @if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
    <div class="d-flex align-items-center">
      <i class="fas fa-exclamation-circle me-2"></i>
      <div>
      <ul class="mb-0">
      @foreach ($errors->all() as $error)
      <li>{{ $error }}</li>
      @endforeach
      </ul>
      </div>
    </div>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <form action="{{ route('penjualan.transaksi.store') }}" method="POST" id="sale-form">
    @csrf

    <!-- Customer Information Card -->
    <div class="card shadow-sm mb-4">
      <div class="card-header bg-primary text-white py-3">
      <h6 class="m-0 font-weight-bold">
        <i class="fas fa-user me-2"></i> Informasi Pelanggan
      </h6>
      </div>
      <div class="card-body">
      <div class="row g-3">
        <div class="col-md-6">
        <label for="customer_name" class="form-label">Nama Pelanggan</label>
        <div class="input-group">
          <span class="input-group-text"><i class="fas fa-user"></i></span>
          <input type="text" class="form-control" id="customer_name" name="customer_name"
          value="{{ old('customer_name') }}" placeholder="Nama Pelanggan (Opsional)">
        </div>
        </div>
        <div class="col-md-6">
        <label for="customer_phone" class="form-label">No. Telepon</label>
        <div class="input-group">
          <span class="input-group-text"><i class="fas fa-phone"></i></span>
          <input type="text" class="form-control" id="customer_phone" name="customer_phone"
          value="{{ old('customer_phone') }}" placeholder="No. Telepon (Opsional)">
        </div>
        </div>
        <div class="col-12">
        <label for="notes" class="form-label">Catatan</label>
        <textarea class="form-control" id="notes" name="notes" rows="2"
          placeholder="Catatan tambahan (Opsional)">{{ old('notes') }}</textarea>
        </div>
      </div>
      </div>
    </div>

    <!-- Items Selection Card -->
    <div class="card shadow-sm mb-4">
      <div class="card-header bg-info text-white py-3">
      <h6 class="m-0 font-weight-bold">
        <i class="fas fa-shopping-cart me-2"></i> Pilih Barang
      </h6>
      </div>
      <div class="card-body">
      <div class="row g-3 mb-4">
        <div class="col-md-8">
        <label for="item_select" class="form-label">Cari Barang</label>
        <select class="form-select select2" id="item_select">
          <option value="">-- Pilih Barang --</option>
          @foreach ($items as $item)
        <option value="{{ $item->id }}" data-name="{{ $item->name }}" data-price="{{ $item->price }}"
        data-stock="{{ $item->stock }}">
        {{ $item->name }} (Stok: {{ $item->stock }} {{ $item->unit ?? '' }})
        </option>
      @endforeach
        </select>
        </div>
      </div>

      <div class="table-responsive">
        <table class="table table-hover mb-0" id="selected-items-table">
        <thead class="bg-light">
          <tr>
          <th width="35%">Nama Barang</th>
          <th class="text-end">Harga</th>
          <th class="text-center">Stok</th>
          <th width="120px">Qty</th>
          <th class="text-end">Subtotal</th>
          <th class="text-center">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <!-- Items will be added here by JavaScript -->
        </tbody>
        </table>
      </div>
      </div>
    </div>

    <!-- Payment Summary Card -->
    <div class="card shadow-sm mb-4">
      <div class="card-header bg-success text-white py-3">
      <h6 class="m-0 font-weight-bold">
        <i class="fas fa-receipt me-2"></i> Ringkasan Pembayaran
      </h6>
      </div>
      <div class="card-body">
      <div class="row g-3 mb-3">
        <div class="col-md-4">
        <div class="form-floating">
          <input type="text" class="form-control" id="total_amount" value="Rp 0" readonly>
          <label for="total_amount">Subtotal</label>
          <input type="hidden" name="total_amount_raw" id="total_amount_raw" value="0">
        </div>
        </div>
        <div class="col-md-4">
        <div class="form-floating">
          <input type="number" class="form-control" id="discount_amount" name="discount_amount"
          value="{{ old('discount_amount', 0) }}" min="0">
          <label for="discount_amount">Diskon (Rp)</label>
        </div>
        </div>
        <div class="col-md-4">
        <div class="form-floating">
          <input type="number" class="form-control" id="tax_amount" name="tax_amount"
          value="{{ old('tax_amount', 0) }}" min="0">
          <label for="tax_amount">Pajak (Rp)</label>
        </div>
        </div>
      </div>

      <div class="row g-3 mb-3">
        <div class="col-md-6">
        <div class="form-floating">
          <input type="text" class="form-control fw-bold fs-5" id="grand_total" value="Rp 0" readonly>
          <label for="grand_total">Grand Total</label>
          <input type="hidden" name="grand_total_raw" id="grand_total_raw" value="0">
        </div>
        </div>
        <div class="col-md-6">
        <div class="form-floating">
          <select class="form-select" id="payment_method" name="payment_method" required>
          <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>Cash</option>
          <option value="transfer" {{ old('payment_method') == 'transfer' ? 'selected' : '' }}>Transfer</option>
          </select>
          <label for="payment_method">Metode Pembayaran</label>
        </div>
        </div>
      </div>

      <div class="row g-3">
        <div class="col-md-6">
        <div class="form-floating">
          <input type="number" class="form-control" id="paid_amount" name="paid_amount"
          value="{{ old('paid_amount', 0) }}" min="0" required>
          <label for="paid_amount">Jumlah Dibayar</label>
        </div>
        </div>
        <div class="col-md-6">
        <div class="form-floating">
          <input type="text" class="form-control fw-bold fs-5 text-primary" id="change_amount" value="Rp 0"
          readonly>
          <label for="change_amount">Kembalian</label>
        </div>
        </div>
      </div>
      </div>
    </div>

    <!-- Action Buttons -->
    <div class="d-grid gap-3 d-md-flex justify-content-md-end">
      <button type="submit" class="btn btn-success btn-sm px-4" id="process-sale-btn">
      <i class="fas fa-check-circle me-2"></i> Proses Penjualan
      </button>
      <button type="reset" class="btn btn-outline-secondary btn-sm px-4">
      <i class="fas fa-undo me-2"></i> Reset
      </button>
    </div>
    </form>
  </div>
@endsection

@push('styles')
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
  <style>
    .card {
    border-radius: 10px;
    overflow: hidden;
    border: none;
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.1);
    }

    .card-header {
    border-bottom: 1px solid rgba(0, 0, 0, 0.05);
    }

    .select2-container .select2-selection--single {
    height: 58px;
    border-color: #ced4da;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
    line-height: 58px;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 58px;
    }

    .form-floating>label {
    padding: 1rem 1.75rem;
    }

    .form-floating>.form-control {
    height: 58px;
    padding: 1rem 1.75rem;
    }

    .form-floating>.form-control:focus~label,
    .form-floating>.form-control:not(:placeholder-shown)~label,
    .form-floating>.form-select~label {
    transform: scale(0.85) translateY(-0.8rem) translateX(0.5rem);
    }

    #selected-items-table th {
    text-transform: uppercase;
    font-size: 0.75rem;
    letter-spacing: 0.5px;
    border-top: none;
    }

    #selected-items-table td {
    vertical-align: middle;
    }

    .quantity-input {
    width: 70px;
    text-align: center;
    }

    @media (max-width: 768px) {
    .d-md-flex {
      flex-direction: column;
    }

    .btn-lg {
      width: 100%;
      margin-bottom: 10px;
    }

    .form-floating>label,
    .form-floating>.form-control,
    .form-floating>.form-select {
      height: 50px;
    }

    .select2-container .select2-selection--single {
      height: 50px;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
      line-height: 50px;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
      height: 50px;
    }
    }
  </style>
@endpush

@push('scripts')
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
  <script>
    $(document).ready(function () {
    // Initialize Select2 with search
    $('.select2').select2({
      placeholder: "Cari atau pilih barang",
      allowClear: true,
      width: '100%'
    });

    var selectedItems = {}; // Store selected items {itemId: {name, price, stock, quantity}}

    // Update selected items table
    function updateSelectedItemsTable() {
      var totalAmount = 0;
      var $tableBody = $('#selected-items-table tbody');
      $tableBody.empty();

      for (var itemId in selectedItems) {
      var item = selectedItems[itemId];
      var subtotal = item.quantity * item.price;
      totalAmount += subtotal;

      var row = `
      <tr>
      <td>
      ${item.name}
      <input type="hidden" name="items[${itemId}][item_id]" value="${itemId}">
      <input type="hidden" name="items[${itemId}][price_per_unit]" value="${item.price}">
      </td>
      <td class="text-end">Rp ${numberFormat(item.price)}</td>
      <td class="text-center">${item.stock}</td>
      <td>
      <input type="number" class="form-control form-control-sm quantity-input"
      name="items[${itemId}][quantity]"
      value="${item.quantity}"
      min="1"
      max="${item.stock}"
      data-item-id="${itemId}"
      data-item-price="${item.price}">
      </td>
      <td class="text-end item-subtotal">Rp ${numberFormat(subtotal)}</td>
      <td class="text-center">
      <button type="button" class="btn btn-danger btn-sm remove-item" data-item-id="${itemId}">
      <i class="fas fa-trash-alt"></i>
      </button>
      </td>
      </tr>
      `;
      $tableBody.append(row);
      }

      $('#total_amount').val('Rp ' + numberFormat(totalAmount));
      $('#total_amount_raw').val(totalAmount);
      calculateGrandTotal();
    }

    // Calculate grand total
    function calculateGrandTotal() {
      var totalAmount = parseFloat($('#total_amount_raw').val()) || 0;
      var discount = parseFloat($('#discount_amount').val()) || 0;
      var tax = parseFloat($('#tax_amount').val()) || 0;
      var grandTotal = totalAmount - discount + tax;

      $('#grand_total').val('Rp ' + numberFormat(grandTotal));
      $('#grand_total_raw').val(grandTotal);
      calculateChange();
    }

    // Calculate change amount
    function calculateChange() {
      var grandTotal = parseFloat($('#grand_total_raw').val()) || 0;
      var paidAmount = parseFloat($('#paid_amount').val()) || 0;
      var changeAmount = paidAmount - grandTotal;

      $('#change_amount').val('Rp ' + numberFormat(changeAmount));

      // Enable/disable process button
      if (paidAmount >= grandTotal && grandTotal > 0 && Object.keys(selectedItems).length > 0) {
      $('#process-sale-btn').prop('disabled', false);
      } else {
      $('#process-sale-btn').prop('disabled', true);
      }
    }

    // Add item from select2
    $('#item_select').change(function () {
      var $selectedOption = $(this).find('option:selected');
      var itemId = $selectedOption.val();
      var itemName = $selectedOption.data('name');
      var itemPrice = $selectedOption.data('price');
      var itemStock = $selectedOption.data('stock');

      if (itemId) {
      if (itemStock <= 0) {
        alert('Stok barang ini habis!');
        $(this).val('').trigger('change');
        return;
      }

      if (!selectedItems[itemId]) {
        selectedItems[itemId] = {
        name: itemName,
        price: itemPrice,
        stock: itemStock,
        quantity: 1
        };
      } else {
        if (selectedItems[itemId].quantity < itemStock) {
        selectedItems[itemId].quantity++;
        } else {
        alert('Kuantitas melebihi stok yang tersedia!');
        }
      }
      updateSelectedItemsTable();
      $(this).val('').trigger('change');
      }
    });

    // Quantity input change
    $('#selected-items-table').on('input', '.quantity-input', function () {
      var itemId = $(this).data('item-id');
      var newQuantity = parseInt($(this).val()) || 0;
      var maxStock = parseInt($(this).attr('max'));

      if (newQuantity > maxStock) {
      newQuantity = maxStock;
      $(this).val(newQuantity);
      alert('Kuantitas melebihi stok yang tersedia!');
      } else if (newQuantity < 1) {
      newQuantity = 1;
      $(this).val(newQuantity);
      }

      if (selectedItems[itemId]) {
      selectedItems[itemId].quantity = newQuantity;
      var subtotal = selectedItems[itemId].quantity * selectedItems[itemId].price;
      $(this).closest('tr').find('.item-subtotal').text('Rp ' + numberFormat(subtotal));
      updateSelectedItemsTable();
      }
    });

    // Remove item
    $('#selected-items-table').on('click', '.remove-item', function () {
      var itemId = $(this).data('item-id');
      delete selectedItems[itemId];
      updateSelectedItemsTable();
    });

    // Calculate on input changes
    $('#discount_amount, #tax_amount, #paid_amount').on('input', function () {
      calculateGrandTotal();
    });

    // Number formatting
    function numberFormat(amount) {
      return new Intl.NumberFormat('id-ID').format(amount);
    }

    // Initialize
    updateSelectedItemsTable();
    });
  </script>
@endpush