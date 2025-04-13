<?php

namespace App\Http\Controllers;

use App\Models\Divisi;
use App\Models\Karyawan;
use Illuminate\Http\Request;

class KaryawanController extends Controller
{
    public function index()
    {
        $karyawan = Karyawan::whereHas('user', function ($query) {
            $query->where('role', 'karyawan');
        })->with(['divisi', 'user'])->get();
        return view('admin.manajemen_karyawan.index', compact('karyawan'));
    }

    public function edit($id)
    {
        $karyawan = Karyawan::findOrFail($id);
        $divisi = Divisi::all();
        return view('admin.manajemen_karyawan.edit', compact('karyawan', 'divisi'));
    }

    public function update(Request $request, $id)
    {
        $karyawan = Karyawan::findOrFail($id);
        $karyawan->update($request->all());
        return redirect()->route('admin.karyawan')->with('success', 'Data karyawan berhasil diupdate');
    }
    public function destroy($id)
    {
        $karyawan = Karyawan::findOrFail($id);
        $karyawan->delete();
        return redirect()->route('admin.karyawan')->with('success', 'Data karyawan berhasil dihapus');
    }
    public function show($id)
    {
        $karyawan = Karyawan::with(['user', 'divisi'])->findOrFail($id);
        return view('admin.manajemen_karyawan.show', compact('karyawan'));
    }
}
