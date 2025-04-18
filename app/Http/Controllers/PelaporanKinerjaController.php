<?php

namespace App\Http\Controllers;

use App\Models\Jurnal;
use App\Models\Karyawan;
use App\Models\Lampiran;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\PelaporanKinerja;
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
            })->with(['karyawan.user'])->get();
        }

        return view('admin.pelaporan_kinerja.index', compact('pelaporan'));
    }

    public function create()
    {
        return view('admin.pelaporan_kinerja.create');
    }

    public function store(Request $request)
    {
        $bulan = PelaporanKinerja::where('periode', now()->format('Y-m'))->first();
        if ($bulan) {
            return redirect()->back()->with('error', 'Laporan sudah ada untuk bulan ini.');
        }

        $request->validate([
            'isi_laporan' => 'required|string',
            'lampiran.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx,mp4|max:20480',
        ]);

        // Cari karyawan berdasarkan user yang sedang login
        $karyawan = Karyawan::where('user_id', auth()->id())->first();

        $periode = now()->format('Y-m'); // Bulanan

        // Simpan laporan
        $pelaporan = PelaporanKinerja::create([
            'karyawan_id' => $karyawan->id,
            'periode' => $periode,
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
                // Menyimpan file lampiran
                $path = $file->store('lampiran', 'public');
                $file_type = $file->getClientOriginalExtension(); // Dapatkan ekstensi file

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
        $mime = $file->getMimeType();
        if (str_starts_with($mime, 'image'))
            return 'image';
        if (str_contains($mime, 'pdf'))
            return 'pdf';
        if (str_contains($mime, 'msword') || str_contains($mime, 'officedocument'))
            return 'doc';
        if (str_contains($mime, 'video'))
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

    public function storeOtomatis(Request $request)
    {
        $user = auth()->user(); // Mengambil pengguna yang sedang login
        $userId = $user->id; // Mendapatkan user_id dari pengguna yang sedang login

        // Cari karyawan_id berdasarkan user_id di tabel karyawan
        $karyawan = Karyawan::where('user_id', $userId)->first();

        // Jika karyawan ditemukan, lanjutkan dengan query jurnal
        if ($karyawan) {
            // Cek apakah sudah ada pelaporan untuk bulan ini
            $existingReport = DB::table('pelaporan_kinerja')
                ->where('karyawan_id', $karyawan->id)
                ->where('periode', now()->format('F Y')) // Cek berdasarkan bulan dan tahun
                ->first();

            // Jika sudah ada pelaporan untuk bulan ini, tampilkan pesan error
            if ($existingReport) {
                return back()->with('error', 'Anda sudah mengirimkan pelaporan untuk bulan ini.');
            }

            // Gunakan karyawan_id untuk mencari jurnal yang disetujui
            $jurnals = Jurnal::where('karyawan_id', $karyawan->id) // karyawan_id dari tabel karyawan
                ->where('status', 'disetujui')
                ->orderBy('tanggal', 'asc')
                ->get();

            // Jika tidak ada jurnal yang disetujui, tampilkan pesan error
            if ($jurnals->isEmpty()) {
                return back()->with('error', 'Tidak ada jurnal harian yang disetujui.');
            }

            // Gabungkan semua jurnal menjadi isi laporan
            $isiLaporan = '';
            foreach ($jurnals as $jurnal) {
                $isiLaporan .= "- [" . date('d M Y', strtotime($jurnal->tanggal)) . "] ";
                $isiLaporan .= $jurnal->judul . "\n";
                $isiLaporan .= strip_tags($jurnal->uraian) . "\n\n";
            }

            // Simpan laporan ke dalam tabel pelaporan_kinerja
            $pelaporanId = DB::table('pelaporan_kinerja')->insertGetId([
                'karyawan_id' => $karyawan->id, // Menggunakan karyawan_id yang ditemukan
                'periode' => now()->format('F Y'), // Contoh: April 2025
                'isi_laporan' => $isiLaporan,
                'status' => 'dikirim',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Validasi dan simpan lampiran jika ada
            if ($request->hasFile('lampiran')) {
                // Validasi jenis file dan ukuran (misalnya hanya image dan max 2MB)
                $request->validate([
                    'lampiran.*' => 'mimes:jpg,jpeg,png,pdf|max:2048',
                ]);

                // Simpan setiap file lampiran
                foreach ($request->file('lampiran') as $file) {
                    $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
                    $path = $file->storeAs('lampiran_pelaporan', $filename, 'public');

                    Log::info($file->getClientMimeType()); // Cek tipe MIME

                    DB::table('lapiran')->insert([
                        'lampiranable_id' => $pelaporanId,
                        'lampiranable_type' => 'App\Models\PelaporanKinerja', // Pastikan model PelaporanKinerja digunakan
                        'file_path' => $path,
                        'file_type' => $file->getClientMimeType(), // Tipe MIME file
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        } else {
            return back()->with('error', 'Karyawan tidak ditemukan.');
        }

        // Redirect kembali dengan pesan sukses
        return redirect()->back()->with('success', 'Pelaporan otomatis berhasil dikirim.');
    }


}
