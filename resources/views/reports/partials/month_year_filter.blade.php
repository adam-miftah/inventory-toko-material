<div class="me-2">
  <label for="month" class="form-label mb-0">Pilih Bulan:</label>
</div>
<div class="me-2">
  <select class="form-control form-control-sm" id="month" name="month">
    @for ($i = 1; $i <= 12; $i++)
    <option value="{{ $i }}" {{ $i == ($filterMonth ?? now()->month) ? 'selected' : '' }}>
      {{ date('F', mktime(0, 0, 0, $i, 1)) }}
    </option>
  @endfor
  </select>
</div>
<div class="me-2">
  <label for="year" class="form-label mb-0">Pilih Tahun:</label>
</div>
<div class="me-2">
  <select class="form-control form-control-sm" id="year" name="year">
    @for ($i = date('Y') - 5; $i <= date('Y'); $i++)
    <option value="{{ $i }}" {{ $i == ($filterYear ?? now()->year) ? 'selected' : '' }}>
      {{ $i }}
    </option>
  @endfor
  </select>
</div>