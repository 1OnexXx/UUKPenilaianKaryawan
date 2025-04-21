<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Jurnal;
use App\Models\Karyawan;
use App\Models\Lampiran;
use Illuminate\Http\Request;
use App\Models\TargetKinerja;
use App\Models\PelaporanKinerja;
use App\Models\KategoriPenilaian;
use App\Models\PenilaianKaryawan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class PenilaianKaryawanController extends Controller
{
    public function index()
    {
        $role = Auth::user()->role;

        if ($role == 'karyawan') {
            $id = Auth::user()->karyawan->id;

            $penilaian = PenilaianKaryawan::whereHas('karyawan', function ($query) use ($id) {
                $query->where('karyawan_id', $id);
            })->with(['karyawan.user', 'penilai', 'kategori'])->get();
        } else {
            $penilaian = PenilaianKaryawan::with(['karyawan.user', 'penilai', 'kategori'])->get();
        }

        return view('admin.penilaian_karyawan.index', compact('penilaian'));
    }

    public function create($karyawan_id)
    {
        $karyawan = Karyawan::with('user')->findOrFail($karyawan_id);
        $kategori = KategoriPenilaian::where('tipe_penilaian', 'subjektif')->get();



        $bulanIni = Carbon::now()->month;
        $tahunIni = Carbon::now()->year;

        $jurnal = Jurnal::with('karyawan.user')
            ->where('karyawan_id', $karyawan_id)
            ->whereMonth('created_at', $bulanIni)
            ->whereIn('status', ['dikirim', 'disetujui'])
            ->whereYear('created_at', $tahunIni)
            ->get();

        $laporan = PelaporanKinerja::with('karyawan.user')
            ->where('karyawan_id', $karyawan_id)
            ->whereMonth('created_at', $bulanIni)
            ->whereYear('created_at', $tahunIni)
            ->get();

        return view('admin.penilaian_karyawan.create', compact('karyawan', 'kategori', 'jurnal', 'laporan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'karyawan_id' => 'required|exists:karyawan,id',
            'nilai' => 'nullable|array',
            'nilai.*' => 'nullable|numeric|min:1|max:100',
            'komentar' => 'nullable|array',
            'komentar.*' => 'nullable|string|max:255',
        ]);

        $karyawan = Karyawan::find($request->karyawan_id);
        if (!$karyawan) {
            return back()->with('error', 'Karyawan tidak ditemukan.');
        }

        $periode = now()->format('Y-m');
        $penilaiId = auth()->id();
        $kategoriList = KategoriPenilaian::all();

        foreach ($kategoriList as $kategori) {
            $nilai = 0;
            $komentar = null;

            if ($kategori->tipe_penilaian === 'subjektif') {
                $nilai = $request->input("nilai.{$kategori->id}");
                $komentar = $request->input("komentar.{$kategori->id}");

                if ($nilai === null)
                    continue; // skip kalau kosong
            } elseif ($kategori->tipe_penilaian === 'objektif') {
                $nilai = $this->hitungNilaiObjektif($kategori, $karyawan);
            }

            // Cek apakah penilai sudah menilai kategori ini pada periode ini
            $sudahAda = PenilaianKaryawan::where([
                ['karyawan_id', $karyawan->id],
                ['kategori_id', $kategori->id],
                ['penilai_id', $penilaiId],
                ['periode', $periode],
            ])->exists();

            if ($sudahAda) {
                // Lewati jika sudah ada
                continue;
            }

            // Simpan penilaian
            PenilaianKaryawan::create([
                'karyawan_id' => $karyawan->id,
                'kategori_id' => $kategori->id,
                'penilai_id' => $penilaiId,
                'nilai' => min($nilai, 100),
                'komentar' => $komentar,
                'periode' => $periode,
            ]);
        }

        return redirect()->route('tim_penilai.riwayat_penilaian')
            ->with('success', 'Penilaian berhasil diproses.');
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

    // app/Http/Controllers/JurnalController.php

    public function showJ($id)
    {
        if (request()->has('back')) {
            session(['previous_url' => request()->query('back')]);
        }
        $jurnal = Jurnal::with('karyawan.user')->findOrFail($id);
        return view('tim_penilai.jurnal.show', compact('jurnal'));
    }

    // app/Http/Controllers/PelaporanKinerjaController.php

    public function showL($id)
    {
        $user = Auth::user();
        $role = $user->role;

        // Ambil semua laporan bulan ini
        $laporanKinerja = PelaporanKinerja::whereMonth('created_at', date('m'))
            ->whereYear('created_at', date('Y'))
            ->get();

        // Ubah status otomatis dari 'dikirim' ke 'ditinjau' jika role adalah tim_penilai atau kepala_sekolah
        if (in_array($role, ['tim_penilai', 'kepala_sekolah'])) {
            foreach ($laporanKinerja as $laporan) {
                if ($laporan->status === 'dikirim') {
                    $laporan->status = 'ditinjau';
                    $laporan->save();
                }
            }
        }
        $laporan = PelaporanKinerja::with('karyawan.user')->findOrFail($id);
        return view('tim_penilai.laporan.show', compact('laporan'));
    }

    public function updatee($id)
    {

        // Cari jurnal berdasarkan ID
        $jurnal = Jurnal::find($id);

        if (!$jurnal) {
            return back()->with('error', 'Jurnal tidak ditemukan.');
        }

        // Validasi data yang diterima dari form
        request()->validate([
            'status' => 'required|string',
            'komentar' => 'nullable|string|max:255',
        ]);

        // Update status berdasarkan input form
        $jurnal->status = request('status');
        $jurnal->komentar = request('komentar');

        // Simpan perubahan
        $jurnal->save();

        return back()->with('success', 'penilaian jurnal berhasil dibuat.');

    }

    public function hitungNilaiObjektif($kategori, $karyawan)
    {
        $nilai = 0;
    
        switch ($kategori->nama_kategori) {
            case 'Ketepatan Waktu':
            case 'Kesesuaian Waktu':
                $laporanList = PelaporanKinerja::with([
                    'targetKinerja' => function ($query) use ($karyawan) {
                        $query->where(function ($q) use ($karyawan) {
                            $q->where('karyawan_id', $karyawan->id)
                                ->orWhere(function ($sub) use ($karyawan) {
                                    $sub->whereNull('karyawan_id')->where('divisi_id', $karyawan->divisi_id);
                                })
                                ->orWhere(function ($sub) {
                                    $sub->whereNull('karyawan_id')->whereNull('divisi_id');
                                });
                        });
                    }
                ])
                ->where('karyawan_id', $karyawan->id)
                ->get();
    
                $totalLaporan = $laporanList->count();
                $tepatWaktu = $laporanList->filter(function ($laporan) {
                    return $laporan->targetKinerja
                        && $laporan->targetKinerja->deadline
                        && $laporan->created_at <= $laporan->targetKinerja->deadline;
                })->count();
    
                Log::info("Total laporan: $totalLaporan, Tepat waktu: $tepatWaktu");
    
                if ($totalLaporan > 0) {
                    $nilai = round(($tepatWaktu / $totalLaporan) * 100);
                }
                break;
    
            case 'Jumlah Laporan':
            case 'Jumlah Dokumen':
                $target = TargetKinerja::where(function ($q) use ($karyawan) {
                    $q->where('karyawan_id', $karyawan->id)
                        ->orWhere(function ($sub) use ($karyawan) {
                            $sub->whereNull('karyawan_id')->where('divisi_id', $karyawan->divisi_id);
                        })
                        ->orWhere(function ($sub) {
                            $sub->whereNull('karyawan_id')->whereNull('divisi_id');
                        });
                })->orderByDesc('id')->first();
    
                if (!$target || $target->target_laporan <= 0) {
                    break;
                }
    
                // Hitung target per karyawan jika targetnya bukan personal
                $jumlahTarget = $target->target_laporan;
    
                if (is_null($target->karyawan_id) && !is_null($target->divisi_id)) {
                    $jumlahKaryawan = Karyawan::where('divisi_id', $target->divisi_id)->count();
                    $jumlahTarget = $jumlahKaryawan > 0 ? $jumlahTarget / $jumlahKaryawan : $jumlahTarget;
                } elseif (is_null($target->karyawan_id) && is_null($target->divisi_id)) {
                    $jumlahKaryawan = Karyawan::count();
                    $jumlahTarget = $jumlahKaryawan > 0 ? $jumlahTarget / $jumlahKaryawan : $jumlahTarget;
                }
    
                if ($kategori->nama_kategori === 'Jumlah Laporan') {
                    $jumlah = PelaporanKinerja::where('karyawan_id', $karyawan->id)->sum('jumlah_laporan');
                } else {
                    $jurnalIds = Jurnal::where('karyawan_id', $karyawan->id)
                        ->where('status', 'disetujui')
                        ->pluck('id');
    
                    $jumlah = Lampiran::whereIn('lampiranable_id', $jurnalIds)
                        ->where('lampiranable_type', Jurnal::class)
                        ->whereIn('file_type', ['pdf', 'doc', 'docx'])
                        ->count();
                }
    
                $nilai = round(($jumlah / $jumlahTarget) * 100);
                break;
    
            default:
                $nilai = 0;
        }
    
        return min($nilai, 100); // Pastikan nilai tidak melebihi 100
    }
    
    public function penilaianOtomatis(Request $request)
    {
        $kategori = KategoriPenilaian::findOrFail($request->kategori_id);
        Log::info('Kategori: ' . $kategori->nama_kategori);
    
        $karyawanId = $request->karyawan_id;
        $karyawan = Karyawan::find($karyawanId);
    
        if (!$karyawan) {
            Log::error('Karyawan tidak ditemukan', ['karyawan_id' => $karyawanId]);
            return response()->json(['error' => 'Karyawan tidak ditemukan'], 404);
        }
    
        // Panggil fungsi hitungNilaiObjektif untuk menghitung nilai
        $nilai = $this->hitungNilaiObjektif($kategori, $karyawan);
    
        Log::info("Nilai akhir untuk {$kategori->nama_kategori}: {$nilai}");
    
        return response()->json(['nilai' => $nilai]);
    }
    
}
