@extends('layouts.app')

@section('title', 'Tambah User Baru')

@section('content')
  <div class="container-fluid">
    <h4>Tambah User Baru</h4>

    <div class="card">
    <div class="card-body">
      <form action="{{ route('settings.users.store') }}" method="POST">
      @csrf

      <div class="mb-3">
        <label for="name" class="form-label">Nama</label>
        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name"
        value="{{ old('name') }}" required>
        @error('name')
      <div class="invalid-feedback">{{ $message }}</div>
      @enderror
      </div>

      <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email"
        value="{{ old('email') }}" required>
        @error('email')
      <div class="invalid-feedback">{{ $message }}</div>
      @enderror
      </div>

      <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password"
        name="password" required>
        @error('password')
      <div class="invalid-feedback">{{ $message }}</div>
      @enderror
      </div>

      <div class="mb-3">
        <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
      </div>

      <div class="mb-3">
        <label for="role" class="form-label">Role</label>
        <select class="form-control @error('role') is-invalid @enderror" id="role" name="role" required>
        <option value="">Pilih Role</option>
        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
        <option value="staff" {{ old('role') == 'staff' ? 'selected' : '' }}>Staff</option>
        {{-- Tambahkan opsi role lain sesuai kebutuhan --}}
        </select>
        @error('role')
      <div class="invalid-feedback">{{ $message }}</div>
      @enderror
      </div>

      <button type="submit" class="btn btn-success btn-sm">Simpan</button>
      <a href="{{ route('settings.users.index') }}" class="btn btn-outline-secondary btn-sm">Batal</a>
      </form>
    </div>
    </div>
  </div>
@endsection