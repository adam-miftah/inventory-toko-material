@extends('layouts.app')

@section('title', 'Manajemen User')

@section('content')
  <div class="container-fluid">
    {{-- Notifikasi Session --}}
    @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
    @if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    {{-- Card Utama --}}
    <div class="card shadow-sm border-0">
      <div class="card-header bg-white p-3">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
          <h4 class="mb-0 fw-bold text-gradient">
            <i class="fas fa-users-cog me-2"></i>Manajemen User
          </h4>
          <a href="{{ route('settings.users.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus-circle me-1"></i> Tambah User Baru
          </a>
        </div>
        <hr class="my-3">
        <div class="row align-items-center g-3">
          <div class="col-md-12">
             <div class="input-group input-group-sm" style="max-width: 350px; margin-left: auto;">
              <span class="input-group-text bg-light border-end-0">
                <i class="fas fa-search text-muted"></i>
              </span>
              <input type="text" id="custom-search-input" class="form-control border-start-0"
                placeholder="Cari berdasarkan nama atau email...">
            </div>
          </div>
        </div>
      </div>

      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0 @if($users->isEmpty()) is-empty @endif" id="users-table" style="width:100%">
            <thead class="table-light">
              <tr>
                <th class="text-center" width="5%">No</th>
                <th>User</th>
                <th class="text-center">Role</th>
                <th class="text-center" width="15%">Aksi</th>
              </tr>
            </thead>
            <tbody>
              @forelse ($users as $user)
            <tr>
            <td data-label="No." class="text-center"></td>
            <td data-label="User">
              <div class="d-flex align-items-center">
              <div class="avatar-sm me-3">{{ substr($user->name, 0, 1) }}</div>
              <div>
                <span class="fw-semibold d-block">{{ $user->name }}</span>
                <small class="text-muted">{{ $user->email }}</small>
              </div>
              </div>
            </td>
            <td data-label="Role" class="text-center">
              @php
          $roleClass = $user->role === 'admin' ? 'primary' : 'secondary';
          @endphp
              <span class="badge bg-{{$roleClass}}-subtle text-{{$roleClass}}-emphasis border border-{{$roleClass}}-subtle rounded-pill">{{ Str::title($user->role) }}</span>
            </td>
            <td data-label="Aksi" class="text-center">
              <div class="d-flex justify-content-center gap-2">
              <a href="{{ route('settings.users.edit', $user->id) }}" class="btn btn-sm btn-outline-warning" data-bs-toggle="tooltip" title="Edit">
                <i class="fas fa-edit"></i>
              </a>
              {{-- Nonaktifkan tombol hapus jika user adalah user yang sedang login --}}
              <button type="button" class="btn btn-sm btn-outline-danger delete-btn"
                data-url="{{ route('settings.users.destroy', $user->id) }}" 
                data-name="{{ $user->name }}" 
                data-bs-toggle="tooltip" 
                title="{{ Auth::id() === $user->id ? 'Tidak dapat menghapus akun sendiri' : 'Hapus' }}"
                {{ Auth::id() === $user->id ? 'disabled' : '' }}>
                <i class="fas fa-trash-alt"></i>
              </button>
              </div>
            </td>
            </tr>
        @empty
          <tr>
          <td colspan="4" class="text-center py-5 text-muted">
            <i class="fas fa-folder-open fa-3x mb-3"></i>
            <p class="mb-0">Tidak ada data user.</p>
          </td>
          </tr>
        @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
@endsection

@push('styles')
  <style>
    .text-gradient {
      background: linear-gradient(135deg, var(--primary), var(--secondary));
      -webkit-background-clip: text;
      background-clip: text;
      color: transparent;
    }
    .table th {
      font-weight: 600;
      font-size: 0.8rem;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }
    .table td {
      vertical-align: middle;
      font-size: 0.875rem;
    }
    .avatar-sm {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      background-color: var(--bs-primary-bg-subtle);
      color: var(--bs-primary-text-emphasis);
      font-weight: 600;
      font-size: 1rem;
    }
    @media (max-width: 768px) {
      .table thead { display: none; }
      .table tr {
        display: block;
        margin-bottom: 1rem;
        border: 1px solid #dee2e6;
        border-radius: .5rem;
        box-shadow: 0 .125rem .25rem rgba(0,0,0,.075);
      }
      .table td {
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid #f0f0f0;
        padding: 0.75rem 1rem;
      }
      .table td::before {
        content: attr(data-label);
        font-weight: 600;
        color: #6c757d;
        margin-right: 1rem;
      }
      .table td:last-child { border-bottom: 0; }
    }
  </style>
@endpush

@push('scripts')
  <script>
    $(document).ready(function () {
      var tableElement = $('#users-table:not(.is-empty)');

      if (tableElement.length) {
        var table = tableElement.DataTable({
          dom: 'rt<"d-flex justify-content-between align-items-center p-3"ip>',
          paging: true,
          searching: true,
          lengthChange: false,
          ordering: true,
          info: true,
          autoWidth: false,
          responsive: false,
          order: [[1, 'asc']],
          language: {
            search: "",
            zeroRecords: "User tidak ditemukan.",
            info: "Menampilkan _START_ - _END_ dari _TOTAL_ user",
            infoEmpty: "Menampilkan 0 user",
            paginate: { next: "›", previous: "‹" }
          },
          columnDefs: [
            { searchable: false, orderable: false, targets: 0, render: (data, type, row, meta) => meta.row + meta.settings._iDisplayStart + 1 },
            { orderable: false, searchable: false, targets: 3 }
          ],
          drawCallback: function(settings) {
            const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
            [...tooltipTriggerList].map(tooltip => new bootstrap.Tooltip(tooltip));
          }
        });

        $('#custom-search-input').on('keyup', function() {
          table.search(this.value).draw();
        });
      }

      $(document).on('click', '.delete-btn:not(:disabled)', function(e) {
        e.preventDefault();
        const button = $(this);
        const url = button.data('url');
        const name = button.data('name');
        const csrfToken = $('meta[name="csrf-token"]').attr('content');

        Swal.fire({
          title: 'Anda Yakin?',
          html: `Menghapus user <b>${name}</b> tidak dapat dibatalkan.`,
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#d33',
          cancelButtonColor: '#6c757d',
          confirmButtonText: 'Ya, Hapus!',
          cancelButtonText: 'Batal'
        }).then((result) => {
          if (result.isConfirmed) {
            $.ajax({
              url: url,
              type: 'POST',
              data: {
                _token: csrfToken,
                _method: 'DELETE'
              },
              success: function(response) {
                if (typeof table !== 'undefined') {
                  table.row(button.closest('tr')).remove().draw(false);
                } else {
                  location.reload();
                }
                Swal.fire('Berhasil!', response.success, 'success');
              },
              error: function(xhr) {
                let msg = 'Terjadi kesalahan. Silakan coba lagi.';
                if (xhr.responseJSON && xhr.responseJSON.error) {
                  msg = xhr.responseJSON.error;
                }
                Swal.fire('Gagal!', msg, 'error');
              }
            });
          }
        });
      });
    });
  </script>
@endpush
