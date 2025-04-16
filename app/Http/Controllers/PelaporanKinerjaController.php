<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use Illuminate\Http\Request;
use App\Models\PelaporanKinerja;
use Illuminate\Support\Facades\Auth;

class PelaporanKinerjaController extends Controller
{
    public function index()
    {
        $userId = auth()->id(); // ambil ID user yang sedang login
        $role = Auth::user()->role;
        if ($role == 'kepala_sekolah' || 'admin') {
            $pelaporan = PelaporanKinerja::with(['karyawan.user'])->get();
        } else {
            $pelaporan = PelaporanKinerja::whereHas('karyawan', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })->with(['karyawan.user'])->get();
        }

        return view('admin.pelaporan_kinerja.index', compact('pelaporan'));
    }

    public function create()
    {
        return view('admin.pelaporan_kinerja.create');
    }

    public function store(Request $request)
    {
        $bulan = PelaporanKinerja::where('periode', now()->format('Y-m'))->first();
        if ($bulan) {
            return redirect()->back()->with('error', 'Laporan sudah ada untuk bulan ini.');
        }

        $request->validate([
            'isi_laporan' => 'required|string',
        ]);

        // Cari karyawan berdasarkan user yang sedang login
        $karyawan = Karyawan::where('user_id', auth()->id())->first();


        // Bisa pilih salah satu:
        $periode = now()->format('Y-m'); // Bulanan
        // $periode = now()->format('o-\WW'); // Mingguan

        PelaporanKinerja::create([
            'karyawan_id' => $karyawan->id,
            'periode' => $periode,
            'isi_laporan' => $request->isi_laporan,
            'status' => 'dikirim', // default
        ]);

        return redirect()->route('karyawan.pelaporan')->with('success', 'Laporan berhasil dikirim.');
    }
    public function edit($id)
    {
        $pelaporan = PelaporanKinerja::findOrFail($id);
        return view('admin.pelaporan_kinerja.edit', compact('pelaporan'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'isi_laporan' => 'required|string',
        ]);

        $pelaporan = PelaporanKinerja::findOrFail($id);

        // Optional: cek apakah yang update itu memang pemilik laporan
        if ($pelaporan->karyawan_id !== auth()->user()->karyawan->id) {
            abort(403, 'Kamu tidak punya izin mengedit laporan ini.');
        }

        // Update laporan
        $pelaporan->isi_laporan = $request->isi_laporan;
        $pelaporan->status = 'dikirim'; // bisa juga tetap 'ditinjau' jika tidak ingin diubah
        $pelaporan->save();

        return redirect()->route('karyawan.pelaporan')->with('success', 'Laporan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $pelaporan = PelaporanKinerja::findOrFail($id);

        // Optional: cek apakah yang hapus itu memang pemilik laporan
        if ($pelaporan->karyawan_id !== auth()->user()->karyawan->id) {
            abort(403, 'Kamu tidak punya izin menghapus laporan ini.');
        }

        $pelaporan->delete();

        return redirect()->route('karyawan.pelaporan')->with('success', 'Laporan berhasil dihapus.');
    }

    public function show($id)
    {

        $pelaporan = PelaporanKinerja::with('karyawan.user')->findOrFail($id);
        $pelaporan->update([
            'status' => 'ditinjau'
        ]);
        return view('admin.pelaporan_kinerja.review', compact('pelaporan'));
    }
}
