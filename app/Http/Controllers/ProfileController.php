<?php

namespace App\Http\Controllers;

use App\Models\Divisi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{

    public function show()
    {
        $user = Auth::user();
        $role = $user->role;

        if ($role === 'karyawan') {
            $user->load('karyawan');
            $divisis = Divisi::all(); // kirim data divisi ke view
        } else {
            $divisis = [];
        }

        return view('profile', compact('user', 'divisis'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        // Validasi
        $validated = $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|min:6',
            'divisi_id' => 'nullable|exists:divisi,id',
            'nip' => 'nullable|string|max:255',
            'jabatan' => 'nullable|string|max:255',
            'no_hp' => 'nullable|string|max:255',
            'tanggal_masuk' => 'nullable|date',
        ]);

        // Update User
        $user->nama_lengkap = $validated['nama_lengkap'];
        $user->email = $validated['email'];
        if ($request->filled('password')) {
            $user->password = Hash::make($validated['password']);
        }
        $user->save();

        // Update Karyawan (jika role-nya karyawan)
        if ($user->role === 'karyawan' && $user->karyawan) {
            $user->karyawan->update([
                'nip' => $validated['nip'] ?? null,
                'jabatan' => $validated['jabatan'] ?? null,
                'no_hp' => $validated['no_hp'] ?? null,
                'divisi_id' => $validated['divisi_id'] ?? null,
                'tanggal_masuk' => $validated['tanggal_masuk'] ?? null,
            ]);
        }

        return redirect()->back()->with('success', 'Profil berhasil diperbarui.');
    }
}
