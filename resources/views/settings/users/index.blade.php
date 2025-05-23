@extends('layouts.app')

@section('title', 'Manajemen User')

@section('content')
  <div class="container-fluid">
    <h4>Manajemen User</h4>

    <div class="mb-3 mt-3">
    <a href="{{ route('settings.users.create') }}" class="btn btn-outline-primary">Tambah User Baru</a>
    </div>

    @if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
    <div class="card-body">
      <table class="table table-striped">
      <thead>
        <tr>
        <th>ID</th>
        <th>Nama</th>
        <th>Email</th>
        <th>Role</th>
        <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        @forelse ($users as $user)
      <tr>
      <td>{{ $loop->iteration }}</td>
      <td>{{ $user->name }}</td>
      <td>{{ $user->email }}</td>
      <td>{{ $user->role }}</td>
      <td>
        <a href="{{ route('settings.users.edit', $user->id) }}" class="btn btn-sm btn-warning">Edit</a>
        <form action="{{ route('settings.users.destroy', $user->id) }}" method="POST" class="d-inline">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-sm btn-danger"
        onclick="return confirm('Apakah Anda yakin ingin menghapus user ini?')">Hapus</button>
        </form>
      </td>
      </tr>
      @empty
      <tr>
      <td colspan="5" class="text-center">Tidak ada user terdaftar.</td>
      </tr>
      @endforelse
      </tbody>
      </table>

      {{ $users->links() }} {{-- Jika menggunakan pagination --}}
    </div>
    </div>
  </div>
@endsection