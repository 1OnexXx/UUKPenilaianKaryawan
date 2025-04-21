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
        $divisiid = $request->input('divisi', 'all');
    
        // Menentukan periode berdasarkan jenis laporan
        switch ($jenis) {
            case 'bulanan':
                $periode = $now->format('F Y');
                break;
            case 'semester':
                $semester = $now->month <= 6 ? 'Semester 1' : 'Semester 2';
                $periode = $semester . ' ' . $now->year;
                break;
            case 'tahunan':
                $periode = $now->year;
                break;
        }
    
        // Menyesuaikan dengan periode bulan dan tahun yang diminta
        if ($request->has('periode')) {
            // Misalnya format periode 'April 2025'
            $periodeRequest = $request->periode; // "April 2025"
            $periodeDate = \Carbon\Carbon::createFromFormat('F Y', $periodeRequest); // Mengubah ke format Carbon
    
            $month = $periodeDate->month;
            $year = $periodeDate->year;
    
            $laporan = LaporanPenilaian::with([
                'karyawan.user',
                'karyawan.divisi',
                'karyawan.jurnal',
                'karyawan.penilaian_karyawan' => function ($query) use ($month, $year) {
                    $query->whereMonth('created_at', $month)
                          ->whereYear('created_at', $year)
                          ->with(['kategori', 'penilai']);
                },
                'karyawan.pelaporan_kinerja' => function ($query) use ($month, $year) {
                    $query->whereMonth('created_at', $month)
                          ->whereYear('created_at', $year);
                },
                'dibuatOleh'
            ])
            ->get();
        } else {
            // Jika tidak ada input periode, gunakan periode default
            $laporan = LaporanPenilaian::with([
                'karyawan.user',
                'karyawan.divisi',
                'karyawan.jurnal',
                'karyawan.penilaian_karyawan' => function ($query) use ($jenis) {
                    $query->when($jenis === 'bulanan', function ($q) {
                        $q->whereMonth('created_at', now()->month)
                          ->whereYear('created_at', now()->year);
                    })->when($jenis === 'semester', function ($q) {
                        $bulan = now()->month;
                        $semester = $bulan <= 6 ? range(1, 6) : range(7, 12);
                        $q->whereIn(DB::raw('MONTH(created_at)'), $semester)
                          ->whereYear('created_at', now()->year);
                    })->when($jenis === 'tahunan', function ($q) {
                        $q->whereYear('created_at', now()->year);
                    })->with(['kategori', 'penilai']);
                },
                'karyawan.pelaporan_kinerja' => function ($query) use ($jenis) {
                    $query->when($jenis === 'bulanan', function ($q) {
                        $q->whereMonth('created_at', now()->month)
                          ->whereYear('created_at', now()->year);
                    })->when($jenis === 'semester', function ($q) {
                        $bulan = now()->month;
                        $semester = $bulan <= 6 ? range(1, 6) : range(7, 12);
                        $q->whereIn(DB::raw('MONTH(created_at)'), $semester)
                          ->whereYear('created_at', now()->year);
                    })->when($jenis === 'tahunan', function ($q) {
                        $q->whereYear('created_at', now()->year);
                    });
                },
                'dibuatOleh'
            ])
            ->get();
        }
    
        // Hitung rata-rata nilai per karyawan
        $dataKaryawan = [];
    
        $groupedByKaryawan = $laporan->groupBy(function ($item) {
            return $item->karyawan->id;
        });
    
        foreach ($groupedByKaryawan as $group) {
            $karyawan = $group->first()->karyawan;
    
            // Ambil semua penilaian dari seluruh laporan milik karyawan ini
            $penilaians = $group->flatMap(function ($item) {
                return $item->karyawan->penilaian_karyawan;
            });
    
            $totalNilai = 0;
            $jumlahPenilaian = 0;
    
            foreach ($penilaians as $penilaian) {
                if ($penilaian->nilai !== null) {
                    $totalNilai += $penilaian->nilai;
                    $jumlahPenilaian++;
                }
            }
    
            $rataRata = $jumlahPenilaian > 0 ? round($totalNilai / $jumlahPenilaian, 2) : 0;
    
            if ($rataRata >= 90) {
                $status = 'A';
            } elseif ($rataRata >= 80) {
                $status = 'B';
            } elseif ($rataRata >= 70) {
                $status = 'C';
            } elseif ($rataRata >= 60) {
                $status = 'D';
            } else {
                $status = 'E';
            }
    
            $dataKaryawan[] = [
                'nama' => $karyawan->user->nama_lengkap,
                'divisi' => $karyawan->divisi->nama_divisi ?? '-',
                'rata_rata' => $rataRata,
                'status' => $status,
                'penilaian' => $penilaians,
            ];
        }
    
        // Download PDF jika diminta
        if ($request->boolean('download_pdf')) {
            return $this->generatePDF($laporan, $jenis, $dataKaryawan, $periode);
        }
    
        // Tampilkan preview
        return $this->previewLaporan($laporan, $jenis, $divisiid, $dataKaryawan, $periode);
    }
    
    // Generate PDF
    private function generatePDF($laporan, $jenis, $dataKaryawan, $periode)
    {
        $pdf = Pdf::loadView('admin.laporan_penilaian.pdf', [
            'laporan' => $laporan,
            'jenis_laporan' => $jenis,
            'dataKaryawan' => $dataKaryawan,
            'periode' => $periode
        ]);
        return $pdf->download('laporan_penilaian_' . $jenis . '.pdf');
    }
    
    // Preview Laporan
    private function previewLaporan($laporan, $jenis, $divisiid, $dataKaryawan, $periode)
    {
        return view('admin.laporan_penilaian.pdf', [
            'laporan' => $laporan,
            'jenis_laporan' => $jenis,
            'divisiid' => $divisiid,
            'dataKaryawan' => $dataKaryawan,
            'periode' => $periode
        ]);
    }
    
}
