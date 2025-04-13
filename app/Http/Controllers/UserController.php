<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Karyawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Tampilkan halaman dashboard user.
     */
    public function index()
    {
        $users = User::get();
        return view('admin.manajemen_pengguna.index' , compact('users'));
    }

    public function create()
    {
        return view('admin.manajemen_pengguna.create');
    }

public function store(Request $request)
{
    $request->validate([
        'nama_lengkap' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:8',
        'role' => 'required|string',
    ]);

    // Buat user baru
    $user = User::create([
        'nama_lengkap' => $request->nama_lengkap,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'role' => $request->role,
    ]);

    // Jika role-nya karyawan, buat juga data di tabel karyawan
    if ($request->role === 'karyawan') {
        Karyawan::create([
            'user_id' => $user->id,
            
        ]);
    }

    return redirect()->route('admin.manajemen_pengguna')->with('success', 'User created successfully.');
}


    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.manajemen_pengguna.edit', compact('user'));
    }

    public function update(Request $request, $id)
{
    $user = User::findOrFail($id); // cari user berdasarkan id

    $request->validate([
        'nama_lengkap' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
        'role' => 'required|string',
        'password' => 'nullable|string|min:8',
    ]);

    $user->nama_lengkap = $request->nama_lengkap;
    $user->email = $request->email;
    $user->role = $request->role;

    // update password kalau diisi
    if ($request->filled('password')) {
        $user->password = Hash::make($request->password);
    }

    $user->save();

    return redirect()->route('admin.manajemen_pengguna')->with('success', 'Data pengguna berhasil diperbarui.');
}


    public function destroy($id)
{
    $user = User::findOrFail($id);
    $user->delete();

    return redirect()->route('admin.manajemen_pengguna')->with('success', 'Pengguna berhasil dihapus.');
}
}
