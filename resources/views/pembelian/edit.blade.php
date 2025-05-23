@extends('layouts.app')

@section('title', 'Edit Pembelian')

@section('content')
  <div class="container-fluid">
    <div class="page-header mb-4">
    <div class="d-flex justify-content-between align-items-center">
      <h4 class="page-title">
      <i class="fas fa-truck-loading me-2"></i> Edit Pembelian
      </h4>
    </div>
    </div>

    <div class="row">
    <div class="col-md-12">
      <div class="card shadow-sm">
      <div class="card-body">
        <form action="{{ route('pembelian.update', $pembelian) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
          <label for="supplier_id" class="form-label">Supplier</label>
          <select class="form-select @error('supplier_id') is-invalid @enderror" id="supplier_id" name="supplier_id"
          required>
          <option value="">Pilih Supplier</option>
          @foreach ($suppliers as $supplier)
        <option value="{{ $supplier->id }}" {{ old('supplier_id', $pembelian->supplier_id) == $supplier->id ? 'selected' : '' }}>{{ $supplier->name }}</option>
      @endforeach
          </select>
          @error('supplier_id')
        <div class="invalid-feedback">{{ $message }}</div>
      @enderror
        </div>

        <div class="mb-3">
          <label for="purchase_date" class="form-label">Tanggal Pembelian</label>
          <input type="date" class="form-control @error('purchase_date') is-invalid @enderror" id="purchase_date"
          name="purchase_date" value="{{ old('purchase_date', $pembelian->purchase_date->toDateString()) }}"
          required>
          @error('purchase_date')
        <div class="invalid-feedback">{{ $message }}</div>
      @enderror
        </div>

        <div class="mb-3">
          <label for="notes" class="form-label">Catatan (Opsional)</label>
          <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes"
          rows="3">{{ old('notes', $pembelian->notes) }}</textarea>
          @error('notes')
        <div class="invalid-feedback">{{ $message }}</div>
      @enderror
        </div>

        <div class="mb-3">
          <label class="form-label">Detail Pembelian</label>
          <div id="purchase-items-container">
          @if(old('items'))
          @foreach(old('items') as $key => $oldItem)
        <div class="card mb-2" id="item-{{ $key + 1 }}">
          <div class="card-body">
          <div class="row g-3 align-items-center">
          <div class="col-md-4">
          <label for="item_id_{{ $key + 1 }}" class="form-label">Produk</label>
          <select
          class="form-select item-select @error('items.' . $key . '.item_id') is-invalid @enderror"
          id="item_id_{{ $key + 1 }}" name="items[{{ $key + 1 }}][item_id]" required>
          <option value="">Pilih Produk</option>
          @foreach ($items as $item)
        <option value="{{ $item->id }}" data-price="{{ $item->price }}" {{ old('items.' . $key . '.item_id') == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
        @endforeach
          </select>
          @error('items.' . $key . '.item_id')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
          </div>
          <div class="col-md-3">
          <label for="quantity_{{ $key + 1 }}" class="form-label">Kuantitas</label>
          <input type="number"
          class="form-control quantity-input @error('items.' . $key . '.quantity') is-invalid @enderror"
          id="quantity_{{ $key + 1 }}" name="items[{{ $key + 1 }}][quantity]"
          value="{{ old('items.' . $key . '.quantity', 1) }}" min="1" required>
          @error('items.' . $key . '.quantity')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
          </div>
          <div class="col-md-3">
          <label for="price_{{ $key + 1 }}" class="form-label">Harga Satuan</label>
          <input type="number" class="form-control price-input" id="price_{{ $key + 1 }}"
          name="items[{{ $key + 1 }}][price]" value="{{ old('items.' . $key . '.price') }}" readonly>
          </div>
          <div class="col-md-2 text-end">
          <button type="button" class="btn btn-sm btn-danger remove-item" data-item-id="{{ $key + 1 }}">
          <i class="fas fa-trash"></i>
          </button>
          </div>
          </div>
          </div>
        </div>
        @endforeach
      @else
          @foreach ($pembelian->items as $itemPembelian)
        <div class="card mb-2" id="item-{{ $loop->index + 1 }}">
          <div class="card-body">
          <div class="row g-3 align-items-center">
          <div class="col-md-4">
          <label for="item_id_{{ $loop->index + 1 }}" class="form-label">Produk</label>
          <select class="form-select item-select" id="item_id_{{ $loop->index + 1 }}"
          name="items[{{ $loop->index + 1 }}][item_id]" required>
          <option value="">Pilih Produk</option>
          @foreach ($items as $item)
        <option value="{{ $item->id }}" data-price="{{ $item->price }}" {{ $itemPembelian->item_id == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
        @endforeach
          </select>
          </div>
          <div class="col-md-3">
          <label for="quantity_{{ $loop->index + 1 }}" class="form-label">Kuantitas</label>
          <input type="number" class="form-control quantity-input" id="quantity_{{ $loop->index + 1 }}"
          name="items[{{ $loop->index + 1 }}][quantity]" value="{{ $itemPembelian->quantity }}" min="1"
          required>
          </div>
          <div class="col-md-3">
          <label for="price_{{ $loop->index + 1 }}" class="form-label">Harga Satuan</label>
          <input type="number" class="form-control price-input" id="price_{{ $loop->index + 1 }}"
          name="items[{{ $loop->index + 1 }}][price]" value="{{ $itemPembelian->unit_price }}" readonly>
          </div>
          <div class="col-md-2 text-end">
          <button type="button" class="btn btn-sm btn-danger remove-item"
          data-item-id="{{ $loop->index + 1 }}">
          <i class="fas fa-trash"></i>
          </button>
          </div>
          </div>
          </div>
        </div>
        @endforeach
      @endif
          </div>
          <button type="button" class="btn btn-sm btn-success" id="add-item">
          <i class="fas fa-plus"></i> Tambah Item
          </button>
        </div>

        <button type="submit" class="btn btn-success btn-sm">Update Pembelian</button>
        <a href="{{ route('pembelian.index') }}" class="btn btn-outline-secondary btn-sm">Batal</a>
        </form>
      </div>
      </div>
    </div>
    </div>
  </div>

  @push('scripts')
    <script>
    $(document).ready(function () {
    let itemCount = $('#purchase-items-container .card').length;

    $('#add-item').click(function () {
      itemCount++;
      let newItem = `
      <div class="card mb-2" id="item-${itemCount}">
      <div class="card-body">
      <div class="row g-3 align-items-center">
      <div class="col-md-4">
      <label for="item_id_${itemCount}" class="form-label">Produk</label>
      <select class="form-select item-select @error('items.${itemCount - 1}.item_id') is-invalid @enderror" id="item_id_${itemCount}" name="items[${itemCount}][item_id]" required>
      <option value="">Pilih Produk</option>
      @foreach ($items as $item)
      <option value="{{ $item->id }}" data-price="{{ $item->price }}">{{ $item->name }}</option>
    @endforeach
      </select>
      @error('items.${itemCount - 1}.item_id')
      <div class="invalid-feedback">{{ $message }}</div>
    @enderror
      </div>
      <div class="col-md-3">
      <label for="quantity_${itemCount}" class="form-label">Kuantitas</label>
      <input type="number" class="form-control quantity-input @error('items.${itemCount - 1}.quantity') is-invalid @enderror" id="quantity_${itemCount}" name="items[${itemCount}][quantity]" value="1" min="1" required>
      @error('items.${itemCount - 1}.quantity')
      <div class="invalid-feedback">{{ $message }}</div>
    @enderror
      </div>
      <div class="col-md-3">
      <label for="price_${itemCount}" class="form-label">Harga Satuan</label>
      <input type="number" class="form-control price-input" id="price_${itemCount}" name="items[${itemCount}][price]" readonly>
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
      $('#purchase-items-container').append(newItem);
    });

    $(document).on('click', '.remove-item', function () {
      const itemId = $(this).data('item-id');
      $('#item-' + itemId).remove();
    });

    $(document).on('change', '.item-select', function () {
      const selectedOption = $(this).find(':selected');
      const price = selectedOption.data('price');
      const itemId = $(this).attr('id').split('_')[2];
      $('#price_' + itemId).val(price);
    });
    });
    </script>
  @endpush
@endsection