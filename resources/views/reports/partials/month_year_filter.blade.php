<div class="col-auto">
  <select name="month" class="form-select form-select-sm">
    @for ($m = 1; $m <= 12; $m++)
    <option value="{{ $m }}" {{ ($filterMonth ?? now()->month) == $m ? 'selected' : '' }}>
      {{ \Carbon\Carbon::create()->month($m)->isoFormat('MMMM') }}
    </option>
  @endfor
  </select>
</div>
<div class="col-auto">
  <select name="year" class="form-select form-select-sm">
    @for ($y = now()->year; $y >= now()->year - 5; $y--)
    <option value="{{ $y }}" {{ ($filterYear ?? now()->year) == $y ? 'selected' : '' }}>
      {{ $y }}
    </option>
  @endfor
  </select>
</div>