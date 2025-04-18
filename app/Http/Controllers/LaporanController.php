<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\LaporanPenilaian;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    // Method untuk melihat preview laporan
    public function laporan(Request $request)
{
    $request->validate([
        'jenis_laporan' => 'required|in:bulanan,semester,tahunan',
        'divisi' => 'nullable',
    ]);

    $now = now();
    $jenis = $request->jenis_laporan;
    $divisiid = $request->input('divisi', 'all');  // Default 'all' jika tidak ada input 'divisi'

    // Ambil data laporan berdasarkan jenis dan divisi
    $laporan = LaporanPenilaian::with([
        'karyawan.user',
        'karyawan.divisi',
        'karyawan.jurnal',
        'karyawan.penilaian_karyawan' => function ($query) use ($jenis) {
            $query->when($jenis === 'bulanan', function ($q) {
                    $q->whereMonth('created_at', now()->month)
                      ->whereYear('created_at', now()->year);
                })
                ->when($jenis === 'semester', function ($q) {
                    $bulan = now()->month;
                    $semester = $bulan <= 6 ? range(1, 6) : range(7, 12);
                    $q->whereIn(DB::raw('MONTH(created_at)'), $semester)
                      ->whereYear('created_at', now()->year);
                })
                ->when($jenis === 'tahunan', function ($q) {
                    $q->whereYear('created_at', now()->year);
                })
                ->with(['kategori', 'penilai']);
        },
        'karyawan.pelaporan_kinerja' => function ($query) use ($jenis) {
            $query->when($jenis === 'bulanan', function ($q) {
                    $q->whereMonth('created_at', now()->month)
                      ->whereYear('created_at', now()->year);
                })
                ->when($jenis === 'semester', function ($q) {
                    $bulan = now()->month;
                    $semester = $bulan <= 6 ? range(1, 6) : range(7, 12);
                    $q->whereIn(DB::raw('MONTH(created_at)'), $semester)
                      ->whereYear('created_at', now()->year);
                })
                ->when($jenis === 'tahunan', function ($q) {
                    $q->whereYear('created_at', now()->year);
                });
        },
        'dibuatOleh'
    ])
    ->when($jenis === 'bulanan', function ($query) use ($now) {
        $query->whereMonth('created_at', $now->month)
              ->whereYear('created_at', $now->year);
    })
    ->when($jenis === 'semester', function ($query) use ($now) {
        $semester = $now->month <= 6 ? range(1, 6) : range(7, 12);
        $query->whereIn(DB::raw('MONTH(created_at)'), $semester)
              ->whereYear('created_at', $now->year);
    })
    ->when($jenis === 'tahunan', function ($query) use ($now) {
        $query->whereYear('created_at', $now->year);
    })
    ->when($divisiid !== 'all', function ($query) use ($divisiid) {
        $query->whereHas('karyawan.divisi', function ($query) use ($divisiid) {
            $query->where('id', $divisiid);
        });
    })
    ->get();

    // Hitung rata-rata per karyawan
    $dataKaryawan = [];

    foreach ($laporan as $data) {
        $totalNilai = 0;
        $jumlahPenilaian = 0;

        foreach ($data->karyawan->penilaian_karyawan as $penilaian) {
            if ($penilaian->nilai) {
                $totalNilai += $penilaian->nilai;
                $jumlahPenilaian++;
            }
        }

        $rataRata = $jumlahPenilaian > 0 ? $totalNilai / $jumlahPenilaian : 0;
        $status = $rataRata >= 80 ? 'Baik' : 'Harus Ditingkatkan';

        $dataKaryawan[] = [
            'nama' => $data->karyawan->user->nama_lengkap,
            'divisi' => $data->karyawan->divisi->nama_divisi ?? '-',
            'rata_rata' => $rataRata,
            'status' => $status,
            'penilaian' => $data->karyawan->penilaian_karyawan,
        ];
    }

    // Jika download PDF
    if ($request->boolean('download_pdf')) {
        return $this->generatePDF($laporan, $jenis, $dataKaryawan);
    }

    // Kalau tidak, tampilkan preview
    return $this->previewLaporan($laporan, $jenis, $divisiid, $dataKaryawan);
}

// Generate PDF
private function generatePDF($laporan, $jenis, $dataKaryawan)
{
    $pdf = Pdf::loadView('admin.laporan_penilaian.pdf', [
        'laporan' => $laporan,
        'jenis_laporan' => $jenis,
        'dataKaryawan' => $dataKaryawan
    ]);
    return $pdf->download('laporan_penilaian_' . $jenis . '.pdf');
}

// Preview Laporan
private function previewLaporan($laporan, $jenis, $divisiid, $dataKaryawan)
{
    return view('admin.laporan_penilaian.pdf', [
        'laporan' => $laporan,
        'jenis_laporan' => $jenis,
        'divisiid' => $divisiid,
        'dataKaryawan' => $dataKaryawan
    ]);
}

}
