<?php

namespace App\Http\Controllers;

use App\Models\Jurnal;
use App\Models\Karyawan;
use App\Models\Lampiran;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\TargetKinerja;
use App\Models\PelaporanKinerja;
use App\Models\KategoriPenilaian;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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
            })->with(['karyawan.user', 'targetKinerja'])->get();

        }

        return view('admin.pelaporan_kinerja.index', compact('pelaporan'));
    }

    public function create()
    {
        $user = auth()->user();
        $karyawan = $user->karyawan;

        if (!$karyawan) {
            return back()->with('error', 'Data karyawan tidak ditemukan.');
        }

        $targets = TargetKinerja::where(function ($query) use ($karyawan) {
            $query->where(function ($sub) {
                $sub->whereNull('karyawan_id')->whereNull('divisi_id'); // Umum
            })
                ->orWhere(function ($sub) use ($karyawan) {
                    $sub->whereNull('karyawan_id')->where('divisi_id', $karyawan->divisi_id); // Divisi
                })
                ->orWhere(function ($sub) use ($karyawan) {
                    $sub->where('karyawan_id', $karyawan->id); // Pribadi
                });
        })->get();

        return view('admin.pelaporan_kinerja.create', compact('targets'));
    }

    public function store(Request $request)
    {
        // dd($request->all());    
        $request->validate([
            'target_kinerja_id' => 'required|exists:target_kinerja,id',
            'isi_laporan' => 'required|string',
            'lampiran.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx,mp4|max:20480',
        ]);

        $karyawan = Karyawan::where('user_id', auth()->id())->first();

        if (!$karyawan) {
            return back()->with('error', 'Data karyawan tidak ditemukan.');
        }

        // Ambil target kinerja yang dipilih
        $target = TargetKinerja::findOrFail($request->target_kinerja_id);

        // Validasi: apakah target ini memang untuk karyawan login?
        $authorized = (
            (is_null($target->karyawan_id) && is_null($target->divisi_id)) || // Umum
            ($target->karyawan_id == $karyawan->id) || // Personal
            (is_null($target->karyawan_id) && $target->divisi_id == $karyawan->divisi_id) || // Divisi
            ($target->karyawan_id == $karyawan->id && $target->divisi_id == $karyawan->divisi_id) // Spesifik
        );

        if (!$authorized) {
            return back()->with('error', 'Kamu tidak berhak melaporkan target ini.');
        }

        // Cek apakah sudah pernah melapor untuk target ini
        $existing = PelaporanKinerja::where('karyawan_id', $karyawan->id)
            ->where('target_kinerja_id', $request->target_kinerja_id)
            ->exists();

        if ($existing) {
            return back()->with('error', 'Kamu sudah mengirim laporan untuk target ini.');
        }



        // Ambil ID jurnal harian yg sudah dikirim oleh karyawan ini
        $jurnalIds = Jurnal::where('karyawan_id', $karyawan->id)
            ->where('status', 'disetujui')
            ->pluck('id');

        // Hitung jumlah lampiran dokumen dari jurnal2 tadi
        $jumlahLampiranDokumen = Lampiran::whereIn('lampiranable_id', $jurnalIds)
            ->where('lampiranable_type', Jurnal::class)
            ->whereIn('file_type', ['pdf', 'doc', 'docx'])
            ->count();

        $jumlah_laporan = $jumlahLampiranDokumen;

        $target = TargetKinerja::find($request->target_kinerja_id);


        $skor_objektif = 0;
        if ($target && $target->target_laporan > 0 && $jumlah_laporan > 0) {
            $skor_objektif = ($jumlah_laporan / $target->target_laporan) * 100;
            $skor_objektif = round(min($skor_objektif, 100), 0); // biar hasilnya antara 0 - 100 dan dibuletin
        }

        // dd($skor_objektif);

        $pelaporan = PelaporanKinerja::create([
            'karyawan_id' => $karyawan->id,
            'target_kinerja_id' => $request->target_kinerja_id,
            'periode' => now()->format('F Y'), // Contoh: April 2025
            'skor_objektif' => $skor_objektif,
            'jumlah_laporan' => $jumlah_laporan,
            'isi_laporan' => $request->isi_laporan,
            'status' => 'dikirim',
        ]);


        // Simpan lampiran jika ada
        if ($request->hasFile('lampiran')) {
            $files = $request->file('lampiran');

            if (count($files) > 5) {
                return back()->with('error', 'Maksimal 5 lampiran diperbolehkan.');
            }

            foreach ($files as $file) {
                $path = $file->store('lampiran', 'public');
                $file_type = $file->getClientOriginalExtension();

                $pelaporan->lampiran2()->create([
                    'file_path' => $path,
                    'file_type' => $file_type,
                ]);
            }
        }

        return redirect()->route('karyawan.pelaporan')->with('success', 'Laporan berhasil dikirim.');
    }


    public function edit($id)
    {
        $pelaporan = PelaporanKinerja::findOrFail($id);
        return view('admin.pelaporan_kinerja.edit', compact('pelaporan'));
    }

    public function update(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'isi_laporan' => 'required|string',
            'lampiran.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx,mp4|max:20480',
        ]);

        // Cari pelaporan berdasarkan ID
        $pelaporan = PelaporanKinerja::findOrFail($id);

        // Optional: cek apakah yang update itu memang pemilik laporan
        if ($pelaporan->karyawan_id !== auth()->user()->karyawan->id) {
            abort(403, 'Kamu tidak punya izin mengedit laporan ini.');
        }

        // Update isi laporan
        $pelaporan->isi_laporan = $request->isi_laporan;
        $pelaporan->status = 'dikirim'; // Bisa juga tetap 'ditinjau' jika tidak ingin diubah
        $pelaporan->save();

        // Cek dan simpan lampiran baru jika ada
        if ($request->hasFile('lampiran')) {
            $files = $request->file('lampiran');

            if (count($files) > 5) {
                return back()->with('error', 'Maksimal 5 lampiran diperbolehkan.');
            }

            // Hapus lampiran lama jika perlu
            foreach ($pelaporan->lampiran2 as $lampiran) {
                Storage::disk('public')->delete($lampiran->file_path);
                $lampiran->delete();
            }

            // Upload dan simpan lampiran baru
            foreach ($files as $file) {
                $path = $file->store('lampiran', 'public');
                $pelaporan->lampiran2()->create([
                    'file_path' => $path,
                    'file_type' => $this->getFileType($file),
                ]);
            }
        }

        return redirect()->route('karyawan.pelaporan')->with('success', 'Laporan berhasil diperbarui.');
    }


    public function destroy($id)
    {
        $pelaporan = PelaporanKinerja::findOrFail($id);

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


    public function uploadLampiran(Request $request, $id)
    {
        $request->validate([
            'lampiran.*' => 'file|mimes:jpg,jpeg,png,pdf,doc,docx,mp4|max:20480',
        ]);

        $jurnal = PelaporanKinerja::findOrFail($id);

        if ($jurnal->lampiran()->count() + count($request->file('lampiran')) > 5) {
            return back()->with('error', 'Maksimal 5 lampiran per jurnal.');
        }

        foreach ($request->file('lampiran') as $file) {
            $path = $file->store('lampiran', 'public');
            $jurnal->lampiran()->create([
                'file_path' => $path,
                'file_type' => $this->getFileType($file),
            ]);
        }

        return back()->with('success', 'Lampiran berhasil diunggah!');
    }

    private function getFileType($file)
    {
        $extension = strtolower($file->getClientOriginalExtension());

        if (in_array($extension, ['jpg', 'jpeg', 'png']))
            return 'image';
        if ($extension === 'pdf')
            return 'pdf';
        if (in_array($extension, ['doc', 'docx']))
            return $extension;
        if (in_array($extension, ['xls', 'xlsx']))
            return $extension;
        if ($extension === 'mp4')
            return 'video';

        return 'unknown';
    }


    public function lihatLampiran($id)
    {

        $role = Auth::user()->role;
        $jurnal = PelaporanKinerja::find($id);

        if (!$jurnal) {
            return redirect()->route($role . '.jurnal')->with('error', 'Jurnal tidak ditemukan.');
        }

        // Ambil lampiran yang terkait dengan jurnal
        $lampiran = $jurnal->lampiran;

        return view('admin.jurnal.lampiran', compact('jurnal', 'lampiran'));
    }

    public function showLampiran($id)
    {
        $lampiran = Lampiran::findOrFail($id);

        // Cek apakah file ada di storage
        if (!Storage::disk('public')->exists($lampiran->file_path)) {
            abort(404, 'Lampiran tidak ditemukan.');
        }

        // Tampilkan file (bisa juga pakai response()->download jika ingin langsung download)
        return response()->file(storage_path('app/public/' . $lampiran->file_path));
    }



}
