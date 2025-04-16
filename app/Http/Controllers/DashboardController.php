<?php

namespace App\Http\Controllers;

use App\Models\Divisi;
use App\Models\Karyawan;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\PenilaianKaryawan;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $now = Carbon::now();
        $total_karyawan = Karyawan::count();

        $data = [
           

            // Jumlah penilaian bulan ini
            'jumlah_penilaian_bulan_ini' => PenilaianKaryawan::whereMonth('created_at', $now->month)
                ->whereYear('created_at', $now->year)
                ->count(),

            // Jumlah penilaian hari ini
            'jumlah_penilaian_hari_ini' => PenilaianKaryawan::whereDate('created_at', $now->toDateString())->count(),

            // Rata-rata semua penilaian
            'rata_rata_nilai' => round(PenilaianKaryawan::avg('nilai'), 2),
        ];
        $topKaryawan = PenilaianKaryawan::with('karyawan.user', 'karyawan.divisi')
        ->select('karyawan_id', DB::raw('AVG(nilai) as rata_rata'))
        ->groupBy('karyawan_id')
        ->orderByDesc('rata_rata')
        ->limit(5)
        ->get();
    
        $nilaiPerBulan = DB::table('penilaian_karyawan')
            ->select(
                DB::raw('SUBSTRING(periode, 6, 2) as bulan'),
                DB::raw('AVG(nilai) as rata_rata')
            )
            ->groupBy(DB::raw('SUBSTRING(periode, 6, 2)'))
            ->orderBy(DB::raw('SUBSTRING(periode, 6, 2)'))
            ->get();

        // Siapkan array 12 bulan, isi default 0
        $nilaiChart = [];
        for ($i = 1; $i <= 12; $i++) {
            $bulan = str_pad($i, 2, '0', STR_PAD_LEFT);
            $nilaiChart[] = (float) ($nilaiPerBulan->firstWhere('bulan', $bulan)->rata_rata ?? 0);
        }

        // Nilai tertinggi
        $nilaiTertinggi = max($nilaiChart);

        // Nilai bulan ini dan sebelumnya
        $nilaiBulanIni = $nilaiChart[$now->month - 1] ?? 0;
        $nilaiBulanLalu = $nilaiChart[($now->month - 2 + 12) % 12] ?? 0;

        // Hitung persentase kenaikan
        $kenaikan = 0;
        if ($nilaiBulanLalu) {
            $kenaikan = (($nilaiBulanIni - $nilaiBulanLalu) / $nilaiBulanLalu) * 100;
        }

        // Ambil divisi dan jumlah karyawan di masing-masing divisi
        $divisi = Divisi::withCount('karyawan')->get();

        // Ambil semua karyawan + relasi detail dan divisinya
        $karyawan = User::with(['detail.divisi'])->where('role', 'karyawan')->get();

        // Ambil daftar divisi unik dari tabel divisis (bukan dari karyawans)
        $divisiList = Divisi::pluck('nama_divisi');

        return view('admin.dashboard', compact(
            'data',
            'topKaryawan',
            'nilaiChart',
            'nilaiTertinggi',
            'kenaikan',
            'divisi',
            'karyawan',
            'total_karyawan',
            'divisiList'
        ));
    }
}
