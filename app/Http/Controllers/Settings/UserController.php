<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    /**
     * Menampilkan daftar semua user.
     */
    public function index()
    {
        // Gunakan get() untuk mengirim semua data ke DataTables
        $users = User::latest()->get();
        return view('settings.users.index', compact('users'));
    }

    /**
     * Menampilkan form untuk membuat user baru.
     */
    public function create()
    {
        return view('settings.users.create');
    }

    /**
     * Menyimpan user baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'in:admin,kasir'],
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect()->route('settings.users.index')->with('success', 'User baru berhasil ditambahkan.');
    }

    /**
     * Menampilkan form untuk mengedit user.
     */
    public function edit(User $user)
    {
        return view('settings.users.edit', compact('user'));
    }

    /**
     * Memperbarui data user di database.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'role' => ['required', 'in:admin,kasir'],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
        ]);

        $userData = $request->only('name', 'email', 'role');
        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }

        $user->update($userData);

        return redirect()->route('settings.users.index')->with('success', 'Data user berhasil diperbarui.');
    }

    /**
     * Menghapus user dari database.
     */
    public function destroy(Request $request, User $user)
    {
        // Pengecekan krusial: jangan biarkan user menghapus dirinya sendiri
        if ($user->id === Auth::id()) {
            $errorMessage = 'Anda tidak dapat menghapus akun Anda sendiri.';
            if ($request->ajax()) {
                return response()->json(['error' => $errorMessage], 403); // 403 Forbidden
            }
            return redirect()->route('settings.users.index')->with('error', $errorMessage);
        }

        $userName = $user->name;
        $user->delete();

        $successMessage = 'User "' . $userName . '" berhasil dihapus.';
        if ($request->ajax()) {
            return response()->json(['success' => $successMessage]);
        }
        
        return redirect()->route('settings.users.index')->with('success', $successMessage);
    }
}
