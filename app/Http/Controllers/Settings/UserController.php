<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    /**
     * Menampilkan daftar semua user.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        $users = User::paginate(10); // Contoh paginasi 10 user per halaman
        return view('settings.users.index', compact('users'));
    }

    /**
     * Menampilkan form untuk membuat user baru.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function create()
    {
        return view('settings.users.create');
    }

    /**
     * Menyimpan user baru ke database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Password::min(8)],
            'role' => 'required|string|in:admin,staff', // Contoh role, sesuaikan dengan kebutuhan
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect()->route('settings.users.index')->with('success', 'User berhasil ditambahkan.');
    }

    /**
     * Menampilkan detail user tertentu.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Contracts\View\View
     */
    public function show(User $user)
    {
        return view('settings.users.show', compact('user'));
    }

    /**
     * Menampilkan form untuk mengedit user tertentu.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Contracts\View\View
     */
    public function edit(User $user)
    {
        return view('settings.users.edit', compact('user'));
    }

    /**
     * Memperbarui informasi user tertentu di database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, User $user)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|string|in:admin,staff', // Contoh role
        ];

        if ($request->filled('password')) {
            $rules['password'] = ['confirmed', Password::min(8)];
        }

        $request->validate($rules);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = $request->role;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('settings.users.index')->with('success', 'User berhasil diperbarui.');
    }

    /**
     * Menghapus user tertentu dari database.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(User $user)
    {
        // Hindari penghapusan diri sendiri (opsional)
        if ($user->id === Auth::user()->id) {
            return back()->with('error', 'Anda tidak dapat menghapus diri sendiri.');
        }

        $user->delete();

        return redirect()->route('settings.users.index')->with('success', 'User berhasil dihapus.');
    }
}