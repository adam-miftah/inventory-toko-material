@extends('layouts.app')

@section('title', 'Buat Retur Pembelian')

@section('content')
  <div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0 fw-bold text-gradient">
      <i class="fas fa-undo-alt me-2"></i> Buat Retur Pembelian
    </h4>
    </div>

    @if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
    <h5 class="alert-heading"><i class="fas fa-exclamation-triangle me-2"></i> Terjadi Kesalahan</h5>
    <p>Mohon periksa kembali isian Anda. Ada beberapa data yang tidak valid.</p>
    <hr>
    <ul class="mb-0">
      @foreach ($errors->all() as $error)
      <li>{{ $error }}</li>
    @endforeach
    </ul>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <form action="{{ route('retur-pembelian.store') }}" method="POST">
    @csrf
    <div class="row g-4">
      {{-- KOLOM KIRI: FORM UTAMA --}}
      <div class="col-lg-8">
      <div class="card shadow-sm border-0 h-100">
        <div class="card-header bg-white p-3">
        <h5 class="mb-0 fw-semibold"><i class="fas fa-file-invoice me-2 text-primary"></i>Informasi Retur</h5>
        </div>
        <div class="card-body">
        <div class="row g-3">
          <div class="col-md-6">
          <label for="pembelian_id" class="form-label required">Nomor Pembelian Asli</label>
          <select class="form-select @error('pembelian_id') is-invalid @enderror" id="pembelian_id"
            name="pembelian_id" required>
            <option value="">Pilih Nomor Pembelian</option>
            @foreach ($pembelians as $pembelian)
        <option value="{{ $pembelian->id }}" {{ old('pembelian_id') == $pembelian->id ? 'selected' : '' }}>
        #{{ $pembelian->id }} - {{ $pembelian->supplier->name }}
        ({{ $pembelian->purchase_date->format('d M Y') }})
        </option>
        @endforeach
          </select>
          @error('pembelian_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
          <div class="col-md-6">
          <label for="retur_date" class="form-label required">Tanggal Retur</label>
          <input type="date" class="form-control @error('retur_date') is-invalid @enderror" id="retur_date"
            name="retur_date" value="{{ old('retur_date', now()->toDateString()) }}" required>
          @error('retur_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
          <div class="col-12">
          <label for="notes" class="form-label">Catatan (Alasan Retur)</label>
          <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" rows="2"
            placeholder="Contoh: Barang rusak, salah kirim, dll.">{{ old('notes') }}</textarea>
          @error('notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
        </div>
        </div>
        <div class="card-header bg-white p-3 border-top">
        <h5 class="mb-0 fw-semibold"><i class="fas fa-boxes me-2 text-primary"></i>Item yang Diretur</h5>
        </div>
        <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table align-middle mb-0">
          <thead class="table-light">
            <tr>
            <th class="ps-3">Produk</th>
            <th class="text-center">Qty Dibeli</th>
            <th style="width: 130px;">Qty Retur</th>
            <th class="text-end pe-3">Harga Satuan</th>
            </tr>
          </thead>
          <tbody id="retur-items-container">
            <tr>
            <td colspan="4" class="text-center text-muted p-4">
              <p class="mb-0">Pilih Nomor Pembelian untuk menampilkan item.</p>
            </td>
            </tr>
          </tbody>
          </table>
        </div>
        </div>
      </div>
      </div>

      {{-- KOLOM KANAN: RINGKASAN & SUBMIT --}}
      <div class="col-lg-4">
      <div class="card shadow-sm border-0 sticky-top" style="top: 20px;">
        <div class="card-header bg-white p-3">
        <h5 class="mb-0 fw-semibold"><i class="fas fa-receipt me-2 text-primary"></i>Ringkasan Retur</h5>
        </div>
        <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-2">
          <span class="text-muted">Total Nilai Retur:</span>
          <span class="fw-bold" id="summary-total-retur">Rp 0</span>
        </div>
        </div>
        <div class="card-footer bg-white p-3">
        <div class="d-grid gap-2">
          <button type="submit" class="btn btn-primary"><i class="fas fa-save me-2"></i>Simpan Retur</button>
          <a href="{{ route('retur-pembelian.index') }}" class="btn btn-secondary">Batal</a>
        </div>
        </div>
      </div>
      </div>
    </div>
    </form>
  </div>
@endsection

@push('styles')

  <style>
    .text-gradient {
    background: linear-gradient(135deg, var(--bs-primary), var(--bs-secondary));
    -webkit-background-clip: text;
    background-clip: text;
    color: transparent;
    }

    .form-label.required::after {
    content: " *";
    color: var(--bs-danger);
    }

    .table th {
    font-weight: 600;
    font-size: 0.8rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    white-space: nowrap;
    }

    .table td {
    vertical-align: middle;
    }

    /* Style untuk Select2 agar sesuai dengan tema Bootstrap 5 */
    .select2-container--bootstrap-5 .select2-selection {
    border-radius: .375rem;
    border: 1px solid #dee2e6;
    }

    .select2-container .select2-selection--single {
    height: 38px !important;
    padding: 0.375rem 0.75rem;
    }

    .select2-container--bootstrap-5 .select2-selection--single .select2-selection__rendered {
    padding-left: 0;
    line-height: 1.5;
    }

    .select2-container--bootstrap-5 .select2-selection--single .select2-selection__arrow {
    top: 50%;
    transform: translateY(-50%);
    }
  </style>
@endpush

@push('scripts')

  <script>
    $(document).ready(function () {
    $('#pembelian_id').select2({
      theme: 'bootstrap-5',
      placeholder: 'Cari atau Pilih Pembelian'
    });

    function calculateTotal() {
      let grandTotal = 0;
      $('.retur-item-row').each(function () {
      const quantity = parseFloat($(this).find('.quantity-input').val()) || 0;
      const price = parseFloat($(this).find('.price-input').val()) || 0;
      grandTotal += quantity * price;
      });
      $('#summary-total-retur').text('Rp ' + grandTotal.toLocaleString('id-ID'));
    }

    $('#pembelian_id').change(function () {
      const pembelianId = $(this).val();
      const container = $('#retur-items-container');
      const initialRow = '<tr><td colspan="4" class="text-center text-muted p-4"><div class="spinner-border spinner-border-sm" role="status"><span class="visually-hidden">Loading...</span></div> Memuat item...</td></tr>';
      container.html(initialRow);

      if (!pembelianId) {
      const emptyRow = '<tr><td colspan="4" class="text-center text-muted p-4"><p class="mb-0">Pilih Nomor Pembelian untuk menampilkan item.</p></td></tr>';
      container.html(emptyRow);
      calculateTotal();
      return;
      }

      $.ajax({
      url: `/pembelian/${pembelianId}/items`,
      type: 'GET',
      success: function (items) {
        container.empty();
        if (items.length > 0) {
        items.forEach(function (item, index) {
          // PERUBAHAN: Template baru menggunakan <tr>
          const itemHtml = `
     <tr class="retur-item-row">
      <td class="ps-3">
        {{-- INI YANG DITAMBAHKAN --}}
        <input type="hidden" name="items[${index}][pembelian_item_id]" value="${item.id}">

        <input type="hidden" name="items[${index}][item_id]" value="${item.item_id}">
        <span class="fw-semibold">${item.item_name}</span>
      </td>
      <td class="text-center">${item.quantity}</td>
      <td>
        <input type="number" class="form-control form-control-sm quantity-input" name="items[${index}][quantity]" value="0" min="0" max="${item.quantity}">

        {{-- NAMA INPUT INI DIPERBAIKI --}}
        <input type="hidden" class="price-input" name="items[${index}][unit_price]" value="${item.unit_price}">
      </td>
      <td class="text-end pe-3">
        Rp ${parseFloat(item.unit_price).toLocaleString('id-ID')}
      </td>
    </tr>`;
          container.append(itemHtml);
        });
        } else {
        const noItemRow = '<tr><td colspan="4" class="text-center text-muted p-4"><p class="mb-0">Tidak ada item yang dapat diretur dari pembelian ini.</p></td></tr>';
        container.html(noItemRow);
        }
        calculateTotal();
      },
      error: function () {
        const errorRow = '<tr><td colspan="4" class="text-center text-danger p-4"><p class="mb-0">Gagal memuat item. Silakan coba lagi.</p></td></tr>';
        container.html(errorRow);
        calculateTotal();
      }
      });
    });

    $(document).on('input', '.quantity-input', function () {
      calculateTotal();
    });

    });
  </script>
@endpush