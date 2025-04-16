<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use App\Models\LaporanPenilaian;

class LaporanController extends Controller
{
    public function generateLaporan(Request $request)
{
    // Validasi input
    $request->validate([
        'jenis_laporan' => 'required|in:bulanan,semester,tahunan',
    ]);

    // Ambil data laporan dengan relasi lengkap
    $laporan = LaporanPenilaian::with([
        'karyawan.user',
        'karyawan.divisi',
        'karyawan.penilaian' => function ($query) use ($request) {
            $query->where('periode', $request->jenis_laporan)
                  ->with(['kategori', 'penilai']);
        },
        'karyawan.jurnal',
        'karyawan.laporan_kinerja' => function ($query) use ($request) {
            $query->where('periode', $request->jenis_laporan);
        },
        'dibuatOleh', // relasi ke user yang membuat laporan
    ])
    ->where('jenis_laporan', $request->jenis_laporan)
    ->get();

    // Cek apakah ingin di-download sebagai PDF
    if ($request->boolean('download_pdf')) {
        $pdf = Pdf::loadView('admin.laporan_penilaian.pdf', compact('laporan'));
        return $pdf->download('laporan_penilaian_' . $request->jenis_laporan . '.pdf');
    }

    // Jika tidak dalam format PDF, tampilkan di view biasa
    return view('admin.laporan_penilaian.index', compact('laporan'));
}


}
