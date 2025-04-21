<?php

namespace App\Http\Controllers;

use App\Models\Divisi;
use App\Models\Karyawan;
use Illuminate\Http\Request;
use App\Models\LaporanPenilaian;
use App\Models\PenilaianKaryawan;
use Illuminate\Support\Facades\Auth;

class LaporanPenilaianController extends Controller
{
    public function index()
    {
        $laporan = LaporanPenilaian::with(['karyawan.user', 'dibuatOleh',])->get();
        $divisiList = Divisi::select('id', 'nama_divisi')->groupBy('nama_divisi', 'id')->get();
        return view('admin.laporan_penilaian.index', compact('laporan', 'divisiList'));	
    }

    public function create()
    {
        $karyawan = Karyawan::with('user')->get();
        return view('admin.laporan_penilaian.create', compact('karyawan'));
    }

    public function store(Request $request)
{
    $role = Auth::user()->role;

    // Validasi input
    $request->validate([
        'karyawan_id' => 'required|exists:karyawan,id',
        'jenis_laporan' => 'required|in:bulanan,semester,tahunan',
        'bulan_awal' => 'required|string',
        'bulan_akhir' => 'required|string',
        'tahun' => 'required|numeric',
        'rekomendasi' => 'nullable|string',
    ]);

    // Gabungkan bulan_awal, bulan_akhir, dan tahun untuk membentuk periode
    $periode = $request->bulan_awal . ' - ' . $request->bulan_akhir . ' ' . $request->tahun;

    // Set waktu berdasarkan jenis laporan
    $now = now();
    switch ($request->jenis_laporan) {
        case 'bulanan':
            $start = $now->copy()->startOfMonth();
            $end = $now->copy()->endOfMonth();
            break;
        case 'semester':
            $start = $request->tahun . '-' . (substr($request->bulan_awal, 0, 3)) . '-01'; // Semester mulai bulan awal
            $end = $request->tahun . '-' . (substr($request->bulan_akhir, 0, 3)) . '-01'; // Semester berakhir bulan akhir
            break;
        case 'tahunan':
            $start = $now->copy()->startOfYear();
            $end = $now->copy()->endOfYear();
            break;
    }

    // Ambil nilai penilaian karyawan dalam periode yang ditentukan
    $penilaian = PenilaianKaryawan::where('karyawan_id', $request->karyawan_id)
        ->whereBetween('created_at', [$start, $end])
        ->pluck('nilai');

    // Hitung rata-rata nilai
    $rata_rata = round($penilaian->avg(), 2);

    // Simpan laporan ke dalam database
    LaporanPenilaian::create([
        'karyawan_id' => $request->karyawan_id,
        'jenis_laporan' => $request->jenis_laporan,
        'periode' => $periode,  // Simpan periode yang sudah digabung
        'rata_rata_nilai' => $rata_rata,
        'rekomendasi' => $request->rekomendasi,
        'dibuat_oleh' => auth()->id(),
    ]);

    return redirect()->route($role . '.laporan_penilaian')->with('success', 'Laporan penilaian berhasil dibuat.');
}


    public function edit($id)
    {
        $laporan = LaporanPenilaian::findOrFail($id);
        $karyawan = Karyawan::with('user')->get();
        return view('admin.laporan_penilaian.edit', compact('laporan', 'karyawan'));
    }

    public function update(Request $request, $id)
    {
        $role = Auth::user()->role;
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
        return redirect()->route($role . '.laporan_penilaian')->with('success', 'Laporan penilaian berhasil diperbarui.');
    }
    public function destroy($id)
    {
        $role = Auth::user()->role;

        $laporan = LaporanPenilaian::findOrFail($id);
        $laporan->delete();

        return redirect()->route($role . '.laporan_penilaian')->with('success', 'Laporan penilaian berhasil dihapus.');
    }

}
