<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use Illuminate\Http\Request;
use App\Models\KategoriPenilaian;
use App\Models\PenilaianKaryawan;

class PenilaianKaryawanController extends Controller
{
    public function index()
    {

        $penilaian = PenilaianKaryawan::with(['karyawan.user', 'penilai', 'kategori'])->get();
        return view('admin.penilaian_karyawan.index', compact('penilaian'));

    }

    public function create($karyawan_id)
    {
        $karyawan = Karyawan::with('user')->findOrFail($karyawan_id);
        $kategori = KategoriPenilaian::all(); // Ambil semua kategori penilaian

        return view('admin.penilaian_karyawan.create', compact('karyawan', 'kategori'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'karyawan_id' => 'required|exists:karyawan,id',
            'kategori_id' => 'required|exists:kategori_penilaian,id',
            'nilai' => 'required|numeric|min:1|max:100',
            'komentar' => 'nullable|string|max:255',
        ]);

        PenilaianKaryawan::create([
            'karyawan_id' => $request->karyawan_id,
            'penilai_id' => auth()->id(), // ID user yang sedang login
            'kategori_id' => $request->kategori_id,
            'nilai' => $request->nilai,
            'komentar' => $request->komentar,
            'periode' => now()->format('Y-m'), // Periode penilaian 
        ]);

        return redirect()->route('tim_penilai.riwayat_penilaian')->with('success', 'Penilaian berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $penilaian = PenilaianKaryawan::findOrFail($id);
        $kategori = KategoriPenilaian::all(); // Ambil semua kategori penilaian

        return view('admin.penilaian_karyawan.edit', compact('penilaian', 'kategori'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'karyawan_id' => 'required|exists:karyawan,id',
            'kategori_id' => 'required|exists:kategori_penilaian,id',
            'nilai' => 'required|numeric|min:1|max:100',
            'komentar' => 'nullable|string|max:255',
        ]);

        $penilaian = PenilaianKaryawan::findOrFail($id);

        $penilaian->update([
            'karyawan_id' => $request->karyawan_id,
            'penilai_id' => auth()->id(), // ID user yang sedang login
            'kategori_id' => $request->kategori_id,
            'nilai' => $request->nilai,
            'komentar' => $request->komentar,
        ]);

        return redirect()->route('tim_penilai.riwayat_penilaian')->with('success', 'Penilaian berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $penilaian = PenilaianKaryawan::findOrFail($id);
        $penilaian->delete();

        return redirect()->route('tim_penilai.riwayat_penilaian')->with('success', 'Penilaian berhasil dihapus.');
    }


}
