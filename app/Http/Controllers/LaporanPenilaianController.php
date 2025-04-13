<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use Illuminate\Http\Request;
use App\Models\LaporanPenilaian;
use App\Models\PenilaianKaryawan;

class LaporanPenilaianController extends Controller
{
    public function index()
    {
        $laporan = LaporanPenilaian::with(['karyawan.user', 'dibuatOleh',])->get();
        return view('admin.laporan_penilaian.index', compact('laporan'));
    }

    public function create()
    {
        $karyawan = Karyawan::with('user')->get();
        return view('admin.laporan_penilaian.create', compact('karyawan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'karyawan_id' => 'required|exists:karyawan,id',
            'jenis_laporan' => 'required|in:bulanan,semester,tahunan',
            'periode' => 'required|string',
            'rekomendasi' => 'nullable|string',
        ]);


        $now = now();
        switch ($request->jenis_laporan) {
            case 'bulanan':
                $start = $now->copy()->startOfMonth();
                $end = $now->copy()->endOfMonth();
                break;
            case 'semester':
                $start = $now->month < 7 ? now()->startOfYear() : now()->startOfYear()->addMonths(6);
                $end = $start->copy()->addMonths(5)->endOfMonth();
                break;
            case 'tahunan':
                $start = $now->copy()->startOfYear();
                $end = $now->copy()->endOfYear();
                break;
        }

        $penilaian = PenilaianKaryawan::where('karyawan_id', $request->karyawan_id)
            ->whereBetween('created_at', [$start, $end])
            ->pluck('nilai');

        // Hitung rata-rata nilai
        $rata_rata = round($penilaian->avg(), 2);

        // Simpan laporan
        LaporanPenilaian::create([
            'karyawan_id' => $request->karyawan_id,
            'jenis_laporan' => $request->jenis_laporan,
            'periode' => $request->periode,
            'rata_rata_nilai' => $rata_rata,
            'rekomendasi' => $request->rekomendasi,
            'dibuat_oleh' => auth()->id(),
        ]);

        return redirect()->route('kepala_sekolah.laporan_penilaian')->with('success', 'Laporan penilaian berhasil dibuat.');
    }

    public function edit($id)
    {
        $laporan = LaporanPenilaian::findOrFail($id);
        $karyawan = Karyawan::with('user')->get();
        return view('admin.laporan_penilaian.edit', compact('laporan', 'karyawan'));
    }

    public function update(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'karyawan_id' => 'required|exists:karyawan,id',
            'jenis_laporan' => 'required|in:bulanan,semester,tahunan',
            'periode' => 'required|string',
            'rekomendasi' => 'nullable|string',
        ]);

        // Ambil laporan penilaian yang ingin diperbarui
        $laporan = LaporanPenilaian::findOrFail($id);

        // Tentukan tanggal mulai dan akhir berdasarkan jenis laporan
        $now = now();
        switch ($request->jenis_laporan) {
            case 'bulanan':
                $start = $now->copy()->startOfMonth();
                $end = $now->copy()->endOfMonth();
                break;
            case 'semester':
                $start = $now->month < 7 ? now()->startOfYear() : now()->startOfYear()->addMonths(6);
                $end = $start->copy()->addMonths(5)->endOfMonth();
                break;
            case 'tahunan':
                $start = $now->copy()->startOfYear();
                $end = $now->copy()->endOfYear();
                break;
        }

        // Ambil penilaian dalam periode yang sesuai
        $penilaian = PenilaianKaryawan::where('karyawan_id', $request->karyawan_id)
            ->whereBetween('created_at', [$start, $end])
            ->pluck('nilai');

        // Hitung rata-rata nilai
        $rata_rata = round($penilaian->avg(), 2);

        // Update laporan penilaian
        $laporan->update([
            'karyawan_id' => $request->karyawan_id,
            'jenis_laporan' => $request->jenis_laporan,
            'periode' => $request->periode,
            'rata_rata_nilai' => $rata_rata,
            'rekomendasi' => $request->rekomendasi,
            'dibuat_oleh' => auth()->id(),
        ]);

        // Redirect kembali ke halaman laporan dengan pesan sukses
        return redirect()->route('kepala_sekolah.laporan_penilaian')->with('success', 'Laporan penilaian berhasil diperbarui.');
    }
    public function destroy($id)
    {
        $laporan = LaporanPenilaian::findOrFail($id);
        $laporan->delete();

        return redirect()->route('kepala_sekolah.laporan_penilaian')->with('success', 'Laporan penilaian berhasil dihapus.');
    }

}
