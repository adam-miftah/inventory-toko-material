@extends('layouts.app')

@section('title', 'Edit Profil Perusahaan')

@section('content')
<div class="container-fluid">
    <h4 class="mb-4 fw-bold text-gradient"><i class="fas fa-edit me-2"></i>Edit Profil Perusahaan</h4>

    @if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <form action="{{ route('settings.company.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="row g-3">
                    <div class="col-12">
                        <label for="name" class="form-label required">Nama Perusahaan</label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $company->name) }}" required>
                    </div>
                    <div class="col-12">
                        <label for="address" class="form-label">Alamat</label>
                        <textarea class="form-control" id="address" name="address" rows="3">{{ old('address', $company->address) }}</textarea>
                    </div>
                    <div class="col-md-6">
                        <label for="phone" class="form-label">Telepon</label>
                        <input type="tel" class="form-control" id="phone" name="phone" value="{{ old('phone', $company->phone) }}">
                    </div>
                    <div class="col-md-6">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $company->email) }}">
                    </div>
                    <div class="col-12">
                        <label for="logo" class="form-label">Logo Perusahaan</label>
                        <input class="form-control" type="file" id="logo" name="logo" onchange="previewLogo(event)">
                        <small class="text-muted">Kosongkan jika tidak ingin mengubah logo. Ukuran maks: 2MB.</small>
                        @if ($company->logo)
                            <div class="mt-3">
                                <img id="logo-preview" src="{{ asset('storage/' . $company->logo) }}" alt="Logo saat ini" class="img-thumbnail" width="150">
                            </div>
                        @else
                             <div class="mt-3">
                                <img id="logo-preview" src="#" alt="Pratinjau Logo" class="img-thumbnail" width="150" style="display:none;">
                            </div>
                        @endif
                    </div>
                </div>
                <div class="d-flex justify-content-end mt-4">
                    <a href="{{ route('settings.company.index') }}" class="btn btn-outline-secondary me-2">Batal</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function previewLogo(event) {
        const reader = new FileReader();
        reader.onload = function(){
            const output = document.getElementById('logo-preview');
            output.src = reader.result;
            output.style.display = 'block';
        };
        reader.readAsDataURL(event.target.files[0]);
    }
</script>
@endpush
