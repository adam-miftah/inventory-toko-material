@extends('layouts.app')

@section('title', 'Tambah Jenis Barang Baru')

@section('content')
<div class="container-fluid">
    {{-- HEADER HALAMAN --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0 fw-bold text-gradient">
            <i class="fas fa-tags me-2"></i> Tambah Jenis Barang Baru
        </h4>
    </div>

    {{-- ALERTS (Sekarang akan ditangani oleh SweetAlert) --}}
    <div id="alert-container"></div>
    
    {{-- PERUBAHAN: Menambahkan id pada form --}}
    <form action="{{ route('inventory.categories.store') }}" method="POST" id="create-category-form">
        @csrf
        <div class="row g-4">
            {{-- KOLOM KIRI: FORM UTAMA --}}
            <div class="col-lg-8">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header bg-white p-3">
                        <h5 class="mb-0 fw-semibold"><i class="fas fa-file-alt me-2 text-primary"></i>Detail Jenis Barang</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            {{-- Nama Jenis Barang --}}
                            <div class="col-12">
                                <label for="name" class="form-label required">Nama Jenis Barang</label>
                                <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" placeholder="Contoh: Cat Tembok Interior, Keramik Dinding" required>
                                <div class="invalid-feedback"></div>
                            </div>

                            {{-- Tipe Barang --}}
                            <div class="col-12">
                                <label for="type" class="form-label required">Tipe Barang</label>
                                <select class="form-select" id="type" name="type" required>
                                    <option value="">Pilih tipe...</option>
                                    <option value="general" {{ old('type') == 'general' ? 'selected' : '' }}>Umum</option>
                                    <option value="keramik" {{ old('type') == 'keramik' ? 'selected' : '' }}>Keramik</option>
                                    <option value="cat" {{ old('type') == 'cat' ? 'selected' : '' }}>Cat</option>
                                    <option value="luar" {{ old('type') == 'luar' ? 'selected' : '' }}>Barang Luar</option>
                                </select>
                                <div class="form-text">
                                    Pilih tipe barang untuk menentukan kolom data spesifik yang akan digunakan.
                                </div>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- KOLOM KANAN: AKSI --}}
            <div class="col-lg-4">
                <div class="card shadow-sm border-0 sticky-top" style="top: 20px;">
                     <div class="card-header bg-white p-3">
                        <h5 class="mb-0 fw-semibold"><i class="fas fa-cog me-2 text-primary"></i>Aksi</h5>
                    </div>
                    <div class="card-body">
                        <p class="text-muted">Pastikan semua data yang ditandai dengan bintang (*) telah terisi sebelum menyimpan.</p>
                    </div>
                    <div class="card-footer bg-white p-3">
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary" id="submit-button">
                                <i class="fas fa-save me-2"></i>Simpan Jenis Barang
                            </button>
                            <a href="{{ route('inventory.categories.index') }}" class="btn btn-outline-secondary">Batal</a>
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
</style>
@endpush

@push('scripts')
{{-- SweetAlert2 dan jQuery harus sudah ada di layout utama Anda --}}
<script>
$(document).ready(function() {
    $('#create-category-form').on('submit', function(e) {
        e.preventDefault(); // Mencegah form melakukan submit biasa

        const form = $(this);
        const url = form.attr('action');
        const formData = form.serialize();
        const submitButton = $('#submit-button');
        
        // Nonaktifkan tombol untuk mencegah klik ganda
        submitButton.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Menyimpan...');

        // Hapus status error sebelumnya
        $('.form-control, .form-select').removeClass('is-invalid');
        $('.invalid-feedback').text('');

        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            success: function(response) {
                Swal.fire({
                    title: 'Berhasil!',
                    text: response.success, // Ambil pesan sukses dari controller
                    icon: 'success',
                    timer: 2000,
                    showConfirmButton: false,
                    timerProgressBar: true
                }).then(() => {
                    // Redirect ke halaman index setelah notifikasi ditutup
                    window.location.href = "{{ route('inventory.categories.index') }}";
                });
            },
            error: function(xhr) {
                // Aktifkan kembali tombol
                submitButton.prop('disabled', false).html('<i class="fas fa-save me-2"></i>Simpan Jenis Barang');

                if (xhr.status === 422) { // Error validasi
                    const errors = xhr.responseJSON.errors;
                    let errorMessages = '<ul>';
                    
                    $.each(errors, function(key, value) {
                        // Tampilkan pesan error di bawah setiap input
                        $(`#${key}`).addClass('is-invalid');
                        $(`#${key}`).closest('.col-12').find('.invalid-feedback').text(value[0]);
                        errorMessages += `<li>${value[0]}</li>`;
                    });
                    errorMessages += '</ul>';

                    Swal.fire({
                        title: 'Gagal Validasi',
                        html: errorMessages,
                        icon: 'error'
                    });

                } else { // Error server lainnya
                    Swal.fire(
                        'Terjadi Kesalahan!',
                        'Tidak dapat memproses permintaan Anda. Silakan coba lagi nanti.',
                        'error'
                    );
                }
            }
        });
    });
});
</script>
@endpush
