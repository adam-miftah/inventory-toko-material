@extends('layouts.app')

@section('title', 'Tambah User Baru')

@section('content')
  <div class="container-fluid">
    {{-- HEADER HALAMAN --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0 fw-bold text-gradient">
      <i class="fas fa-user-plus me-2"></i> Tambah User Baru
    </h4>
    </div>

    {{-- ALERTS --}}
    @if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
    <h5 class="alert-heading"><i class="fas fa-exclamation-triangle me-2"></i> Terjadi Kesalahan</h5>
    <p>Mohon periksa kembali isian Anda. Ada beberapa data yang tidak valid.</p>
    <hr>
    <ul class="mb-0 ps-3">
      @foreach ($errors->all() as $error)
      <li>{{ $error }}</li>
    @endforeach
    </ul>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="card shadow-sm border-0">
    <div class="card-body">
      <form action="{{ route('settings.users.store') }}" method="POST">
      @csrf
      <div class="row g-3">
        {{-- Nama Lengkap --}}
        <div class="col-md-6">
        <label for="name" class="form-label required">Nama Lengkap</label>
        <div class="input-group">
          <span class="input-group-text"><i class="fas fa-user"></i></span>
          <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name"
          value="{{ old('name') }}" required placeholder="Masukkan nama lengkap">
          @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        </div>

        {{-- Email --}}
        <div class="col-md-6">
        <label for="email" class="form-label required">Email</label>
        <div class="input-group">
          <span class="input-group-text"><i class="fas fa-envelope"></i></span>
          <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email"
          value="{{ old('email') }}" required placeholder="contoh@email.com">
          @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        </div>

        {{-- Role --}}
        <div class="col-md-12">
        <label for="role" class="form-label required">Role</label>
        <select class="form-select @error('role') is-invalid @enderror" id="role" name="role" required>
          <option value="">Pilih Role...</option>
          <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
          <option value="kasir" {{ old('role') == 'kasir' ? 'selected' : '' }}>Kasir</option>
        </select>
        @error('role')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="col-12">
        <hr class="my-2">
        </div>

        {{-- Password --}}
        <div class="col-md-6">
        <label for="password" class="form-label required">Password</label>
        <div class="input-group">
          <span class="input-group-text"><i class="fas fa-lock"></i></span>
          <input type="password" class="form-control @error('password') is-invalid @enderror" id="password"
          name="password" required>
        </div>
        @error('password')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
        </div>

        {{-- Konfirmasi Password --}}
        <div class="col-md-6">
        <label for="password_confirmation" class="form-label required">Konfirmasi Password</label>
        <div class="input-group">
          <span class="input-group-text"><i class="fas fa-lock"></i></span>
          <input type="password" class="form-control" id="password_confirmation" name="password_confirmation"
          required>
        </div>
        </div>
      </div>

      {{-- Tombol Aksi --}}
      <div class="d-flex justify-content-end mt-4">
        <a href="{{ route('settings.users.index') }}" class="btn btn-secondary me-2">Batal</a>
        <button type="submit" class="btn btn-primary">
        <i class="fas fa-save me-2"></i>Simpan User
        </button>
      </div>
      </form>
    </div>
    </div>
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

    .input-group-text {
    width: 42px;
    justify-content: center;
    }
  </style>
@endpush