<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use App\Models\LaporanPenilaian;

class LaporanController extends Controller
{
    public function generateLaporan(Request $request)
    {
        // Validasi input jika diperlukan
        $request->validate([
            'jenis_laporan' => 'required|in:bulanan,semester,tahunan',
        ]);

        // Ambil laporan berdasarkan jenis laporan yang dipilih
        $laporan = LaporanPenilaian::where('jenis_laporan', $request->jenis_laporan)
            ->with('karyawan.user') // Relasi ke tabel karyawan dan user untuk mengambil nama dan email
            ->get();

        // Jika diminta dalam bentuk PDF
        if ($request->has('download_pdf') && $request->download_pdf == 'true') {
            // Load view yang akan dijadikan PDF
            $pdf = Pdf::loadView('admin.laporan_penilaian.pdf', compact('laporan'));
            // Download file PDF
            return $pdf->download('laporan_penilaian_' . $request->jenis_laporan . '.pdf');
        }

        // Jika tidak dalam format PDF, kembalikan ke view biasa
        return view('admin.laporan_penilaian.index', compact('laporan'));
    }

}
