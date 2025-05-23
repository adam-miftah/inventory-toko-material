@extends('layouts.app')

@section('title', 'Profil Perusahaan')

@section('content')
  <div class="container-fluid">
    <h4>Profil Perusahaan</h4>

    <div class="card mt-3">
    <div class="card-body">
      <form action="{{ route('settings.company.update') }}" method="POST">
      @csrf
      @method('PUT')

      <div class="mb-3">
        <label for="company_name" class="form-label">Nama Perusahaan</label>
        <input type="text" class="form-control @error('company_name') is-invalid @enderror" id="company_name"
        name="company_name" value="{{ old('company_name', $company->company_name ?? '') }}" required>
        @error('company_name')
      <div class="invalid-feedback">{{ $message }}</div>
      @enderror
      </div>

      <div class="mb-3">
        <label for="address" class="form-label">Alamat</label>
        <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="3"
        required>{{ old('address', $company->address ?? '') }}</textarea>
        @error('address')
      <div class="invalid-feedback">{{ $message }}</div>
      @enderror
      </div>

      <div class="mb-3">
        <label for="phone" class="form-label">Nomor Telepon</label>
        <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone"
        value="{{ old('phone', $company->phone ?? '') }}">
        @error('phone')
      <div class="invalid-feedback">{{ $message }}</div>
      @enderror
      </div>

      <div class="mb-3">
        <label for="email" class="form-label">Email Perusahaan</label>
        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email"
        value="{{ old('email', $company->email ?? '') }}">
        @error('email')
      <div class="invalid-feedback">{{ $message }}</div>
      @enderror
      </div>

      {{-- Tambahkan field lain sesuai kebutuhan profil perusahaan Anda --}}

      <button type="submit" class="btn btn-success btn-sm">Simpan Perubahan</button>
      </form>
    </div>
    </div>
  </div>
@endsection