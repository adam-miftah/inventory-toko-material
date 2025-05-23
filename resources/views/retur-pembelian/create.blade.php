@extends('layouts.app')

@section('title', 'Buat Retur Pembelian')

@section('content')
  <div class="container-fluid">
    <div class="page-header mb-4">
    <div class="d-flex justify-content-between align-items-center">
      <h4 class="page-title"><i class="fas fa-undo-alt me-2"></i> Buat Retur Pembelian</h4>
    </div>
    </div>

    <div class="row">
    <div class="col-md-12">
      <div class="card shadow-sm">
      <div class="card-body">
        <form action="{{ route('retur-pembelian.store') }}" method="POST">
        @csrf

        <div class="mb-3">
          <label for="pembelian_id" class="form-label">Nomor Pembelian</label>
          <select class="form-select @error('pembelian_id') is-invalid @enderror" id="pembelian_id"
          name="pembelian_id" required>
          <option value="">Pilih Nomor Pembelian</option>
          @foreach ($pembelians as $pembelian)
        <option value="{{ $pembelian->id }}" {{ old('pembelian_id') == $pembelian->id ? 'selected' : '' }}>
        {{ $pembelian->id }} - {{ $pembelian->supplier->name }}
        ({{ $pembelian->purchase_date->format('d M Y') }})
        </option>
      @endforeach
          </select>
          @error('pembelian_id')
        <div class="invalid-feedback">{{ $message }}</div>
      @enderror
        </div>

        <div class="mb-3">
          <label for="retur_date" class="form-label">Tanggal Retur</label>
          <input type="date" class="form-control @error('retur_date') is-invalid @enderror" id="retur_date"
          name="retur_date" value="{{ old('retur_date', now()->toDateString()) }}" required>
          @error('retur_date')
        <div class="invalid-feedback">{{ $message }}</div>
      @enderror
        </div>

        <div class="mb-3">
          <label for="notes" class="form-label">Catatan (Opsional)</label>
          <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes"
          rows="3">{{ old('notes') }}</textarea>
          @error('notes')
        <div class="invalid-feedback">{{ $message }}</div>
      @enderror
        </div>

        <div class="mb-3">
          <label class="form-label">Item yang Diretur</label>
          <div id="retur-items-container">
          </div>
          <button type="button" class="btn btn-sm btn-info" id="add-item">
          <i class="fas fa-plus"></i> Tambah Item Retur
          </button>
        </div>

        <button type="submit" class="btn btn-outline-primary btn-sm">Simpan Retur Pembelian</button>
        <a href="{{ route('retur-pembelian.index') }}" class="btn btn-outline-secondary btn-sm">Batal</a>
        </form>
      </div>
      </div>
    </div>
    </div>
  </div>

  @push('scripts')
    <script>
    $(document).ready(function () {
    let itemCount = 0;

    $('#pembelian_id').change(function () {
      $('#retur-items-container').empty();
      itemCount = 0;
      const pembelianId = $(this).val();
      if (pembelianId) {
      $.ajax({
      url: `/pembelian/${pembelianId}/items`, // Buat route ini
      type: 'GET',
      success: function (data) {
      if (data.length > 0) {
        data.forEach(function (item) {
        itemCount++;
        let newItem = `
      <div class="card mb-2" id="retur-item-${itemCount}">
      <div class="card-body">
      <div class="row g-3 align-items-center">
      <div class="col-md-4">
      <label for="item_id_${itemCount}" class="form-label">Produk</label>
      <input type="hidden" name="items[${itemCount}][pembelian_item_id]" value="${item.id}">
      <input type="hidden" name="items[${itemCount}][item_id]" value="${item.item_id}">
      <input type="text" class="form-control" value="${item.item_name}" readonly>
      </div>
      <div class="col-md-3">
      <label for="quantity_${itemCount}" class="form-label">Kuantitas Retur</label>
      <input type="number" class="form-control quantity-input" id="quantity_${itemCount}" name="items[${itemCount}][quantity]" value="1" min="1" max="${item.quantity}" required>
      </div>
      <div class="col-md-3">
      <label for="unit_price_${itemCount}" class="form-label">Harga Satuan</label>
      <input type="number" class="form-control unit-price-input" id="unit_price_${itemCount}" name="items[${itemCount}][unit_price]" value="${item.unit_price}" readonly>
      </div>
      <div class="col-md-2 text-end">
      <button type="button" class="btn btn-sm btn-danger remove-item" data-item-id="${itemCount}">
      <i class="fas fa-trash"></i>
      </button>
      </div>
      </div>
      </div>
      </div>
      `;
        $('#retur-items-container').append(newItem);
        });
      } else {
        $('#retur-items-container').html('<p>Tidak ada item untuk diretur dari pembelian ini.</p>');
      }
      }
      });
      }
    });

    $('#add-item').click(function () {
      alert('Fitur tambah item retur manual belum diimplementasikan.');
      // Anda bisa menambahkan logika untuk mencari produk dan menambahkannya secara manual di sini
    });

    $(document).on('click', '.remove-item', function () {
      const itemId = $(this).data('item-id');
      $('#retur-item-' + itemId).remove();
    });
    });
    </script>
  @endpush
@endsection