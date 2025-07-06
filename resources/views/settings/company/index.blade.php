@extends('layouts.app')

@section('title', 'Profil Perusahaan')

@section('content')
  <div class="container-fluid">
    @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="card shadow-sm border-0">
    <div class="card-header bg-white p-3 d-flex justify-content-between align-items-center">
      <h4 class="mb-0 fw-bold text-gradient">
      <i class="fas fa-building me-2"></i>Profil Perusahaan
      </h4>
      <a href="{{ route('settings.company.edit') }}" class="btn btn-primary btn-sm">
      <i class="fas fa-edit me-1"></i> Edit Profil
      </a>
    </div>
    <div class="card-body">
      <div class="row align-items-center">
      <div class="col-md-3 text-center mb-4 mb-md-0">
        <img src="{{ $company->logo ? asset('storage/' . $company->logo) : asset('images/newstruk.png') }}"
        alt="Logo Perusahaan" class="img-fluid rounded-circle"
        style="width: 150px; height: 150px; object-fit: cover; border: 4px solid #f0f0f0;">
      </div>
      <div class="col-md-9">
        <h3 class="fw-bold mb-3">{{ $company->name }}</h3>
        <div class="row">
        <div class="col-lg-6 mb-3">
          <div class="d-flex">
          <i class="fas fa-map-marker-alt fa-fw me-3 mt-1 text-muted"></i>
          <div>
            <span class="fw-semibold ms-1">Alamat</span>
            <p class="mb-0 ms-2">{{ $company->address ?? '-' }}</p>
          </div>
          </div>
        </div>
        <div class="col-lg-6 mb-3">
          <div class="d-flex">
          <i class="fas fa-phone-alt fa-fw me-3 mt-1 text-muted"></i>
          <div>
            <span class="fw-semibold">Telepon</span>
            <p class="mb-0">{{ $company->phone ?? '-' }}</p>
          </div>
          </div>
        </div>
        <div class="col-lg-6 mb-3">
          <div class="d-flex">
          <i class="fas fa-envelope fa-fw me-3 mt-1 text-muted"></i>
          <div>
            <span class="fw-semibold">Email</span>
            <p class="mb-0">{{ $company->email ?? '-' }}</p>
          </div>
          </div>
        </div>
        </div>
      </div>
      </div>
    </div>
    </div>
  </div>
@endsection