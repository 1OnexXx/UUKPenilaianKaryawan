<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\LaporanPenilaian;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    public function generateLaporan(Request $request)
{
    // Validasi input
    $request->validate([
        'jenis_laporan' => 'required|in:bulanan,semester,tahunan',
    ]);

    $now = now();
    $divisiId = $request->input('divisi', 'all');

    $laporan = LaporanPenilaian::with([
        'karyawan.user',
        'karyawan.divisi',
        'karyawan.jurnal',
        'karyawan.penilaian_karyawan' => function ($query) use ($request) {
            $query->when($request->jenis_laporan === 'bulanan', function ($q) {
                    $q->whereMonth('created_at', now()->month)
                      ->whereYear('created_at', now()->year);
                })
                ->when($request->jenis_laporan === 'semester', function ($q) {
                    $bulan = now()->month;
                    $semester = $bulan <= 6 ? range(1, 6) : range(7, 12);
                    $q->whereIn(DB::raw('MONTH(created_at)'), $semester)
                      ->whereYear('created_at', now()->year);
                })
                ->when($request->jenis_laporan === 'tahunan', function ($q) {
                    $q->whereYear('created_at', now()->year);
                })
                ->with(['kategori', 'penilai']);
        },
        'karyawan.pelaporan_kinerja' => function ($query) use ($request) {
            $query->when($request->jenis_laporan === 'bulanan', function ($q) {
                    $q->whereMonth('created_at', now()->month)
                      ->whereYear('created_at', now()->year);
                })
                ->when($request->jenis_laporan === 'semester', function ($q) {
                    $bulan = now()->month;
                    $semester = $bulan <= 6 ? range(1, 6) : range(7, 12);
                    $q->whereIn(DB::raw('MONTH(created_at)'), $semester)
                      ->whereYear('created_at', now()->year);
                })
                ->when($request->jenis_laporan === 'tahunan', function ($q) {
                    $q->whereYear('created_at', now()->year);
                });
        },
        'dibuatOleh'
    ])
    ->when($request->jenis_laporan === 'bulanan', function ($query) use ($now) {
        $query->whereMonth('created_at', $now->month)
              ->whereYear('created_at', $now->year);
    })
    ->when($request->jenis_laporan === 'semester', function ($query) use ($now) {
        $semester = $now->month <= 6 ? range(1, 6) : range(7, 12);
        $query->whereIn(DB::raw('MONTH(created_at)'), $semester)
              ->whereYear('created_at', $now->year);
    })
    ->when($request->jenis_laporan === 'tahunan', function ($query) use ($now) {
        $query->whereYear('created_at', $now->year);
    })
    ->when($divisiId !== 'all', function ($query) use ($divisiId) {
        $query->whereHas('karyawan.divisi', function ($query) use ($divisiId) {
            $query->where('id', $divisiId);
        });
    })
    ->get();

    // =======================
    // Hitung rata-rata PER KARYAWAN
    // =======================
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
            'nama' => $data->karyawan->user->name,
            'divisi' => $data->karyawan->divisi->nama ?? '-',
            'rata_rata' => $rataRata,
            'status' => $status,
            'penilaian' => $data->karyawan->penilaian_karyawan,
        ];
    }

    // =======================
    // Export PDF jika diminta
    // =======================
    if ($request->boolean('download_pdf')) {
        $pdf = Pdf::loadView('admin.laporan_penilaian.pdf', [
            'laporan' => $laporan,
            'jenis_laporan' => $request->jenis_laporan,
            'dataKaryawan' => $dataKaryawan
        ]);
        return $pdf->download('laporan_penilaian_' . $request->jenis_laporan . '.pdf');
    }

    // =======================
    // Tampilkan di view biasa
    // =======================
    return view('admin.laporan_penilaian.index', [
        'laporan' => $laporan,
        'jenis_laporan' => $request->jenis_laporan,
        'dataKaryawan' => $dataKaryawan
    ]);
}




}
