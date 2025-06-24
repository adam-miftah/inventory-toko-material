@extends('layouts.app')

@section('title', 'Tambah Supplier Baru')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0 fw-bold text-gradient">
            <i class="fas fa-user-tie me-2"></i> Tambah Supplier Baru
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

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <form action="{{ route('suppliers.store') }}" method="POST">
                @csrf
                <div class="row g-3">
                    {{-- Nama Supplier --}}
                    <div class="col-md-6">
                        <label for="name" class="form-label required">Nama Supplier</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-building"></i></span>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" placeholder="Contoh: PT. Sumber Jaya" required>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    {{-- Kontak Person --}}
                    <div class="col-md-6">
                        <label for="contact_person" class="form-label">Kontak Person</label>
                        <div class="input-group">
                             <span class="input-group-text"><i class="fas fa-user"></i></span>
                            <input type="text" class="form-control @error('contact_person') is-invalid @enderror" id="contact_person" name="contact_person" value="{{ old('contact_person') }}" placeholder="Nama narahubung">
                            @error('contact_person')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    {{-- Telepon --}}
                    <div class="col-md-6">
                        <label for="phone" class="form-label">Telepon</label>
                         <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-phone-alt"></i></span>
                            <input type="tel" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone') }}" placeholder="0812xxxxxxxx">
                            @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    {{-- Email --}}
                    <div class="col-md-6">
                        <label for="email" class="form-label">Email</label>
                        <div class="input-group">
                             <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" placeholder="contoh@email.com">
                             @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    {{-- Alamat --}}
                    <div class="col-12">
                        <label for="address" class="form-label">Alamat</label>
                        <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="3" placeholder="Alamat lengkap supplier">{{ old('address') }}</textarea>
                        @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                {{-- Tombol Aksi --}}
                <div class="d-flex justify-content-end mt-4">
                    <a href="{{ route('suppliers.index') }}" class="btn btn-outline-secondary me-2">Batal</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Simpan Supplier
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
