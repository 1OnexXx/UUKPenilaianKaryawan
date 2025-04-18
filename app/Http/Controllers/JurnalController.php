<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Jurnal;
use App\Models\Karyawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class JurnalController extends Controller
{
    public function index()
    {
        $userId = auth()->id(); // ambil ID user yang sedang login
        $role = Auth::user()->role;

        if ($role == 'karyawan') {
            $jurnal = Jurnal::whereHas('karyawan', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })->whereIn('status', ['draft', 'dikirim']) // status milik jurnal
                ->with(['karyawan.user'])->get();
        } else {
            $jurnal = Jurnal::all();
        }

        return view('admin.jurnal.index', compact('jurnal'));
    }

    public function history()
    {
        $userId = auth()->id();
        $role = Auth::user()->role;

        if ($role == 'karyawan') {
            $jurnal = Jurnal::whereHas('karyawan', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
                ->whereIn('status', ['ditolak', 'disetujui']) // <-- pindah ke sini
                ->with(['karyawan.user'])
                ->get();
        } else {
            $jurnal = Jurnal::all();
        }

        return view('admin.jurnal.history', compact('jurnal'));
    }


    public function create()
{
    $user = Auth::user();
    $role = $user->role;

    // Cek apakah user punya divisi (khusus karyawan)
    if ($role === 'karyawan') {
        $divisi = $user->karyawan->divisi ?? null;

        if (empty($divisi)) {
            return redirect()->route('karyawan.jurnal')->with('error', 'Anda tidak memiliki akses. Masuk divisi terlebih dahulu.');
        }
    }

    // Jika admin, ambil semua data karyawan
    if ($role === 'admin') {
        $karyawan = Karyawan::with('user')->get();
        return view('admin.jurnal.create', compact('karyawan'));
    }

    // Untuk role lainnya (karyawan, tim_penilai, kepala_sekolah)
    return view("admin.jurnal.create");
}

    public function store(Request $request)
    {
        $role = Auth::user()->role;

        $request->validate([
            'uraian' => 'required|string',
            'judul' => 'required|string',
            'lampiran.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx,mp4|max:20480',
        ]);

        // Ambil karyawan berdasarkan user yang login
        $karyawan = Karyawan::where('user_id', auth()->id())->first();
        if (!$karyawan) {
            return back()->with('error', 'Data karyawan tidak ditemukan.');
        }

        // Buat penilaian kinerja baru
        $penilaian = Jurnal::create([
            'karyawan_id' => $karyawan->id,
            'uraian' => $request->uraian,
            'judul' => $request->judul,
            'status' => 'draft',
            'tanggal' => Carbon::today(),
        ]);

        // Simpan lampiran jika ada
        if ($request->hasFile('lampiran')) {
            $files = $request->file('lampiran');

            if (count($files) > 5) {
                return back()->with('error', 'Maksimal 5 lampiran diperbolehkan.');
            }

            foreach ($files as $file) {
                $path = $file->store('lampiran', 'public');
                $penilaian->lampiran()->create([
                    'file_path' => $path,
                    'file_type' => $this->getFileType($file),
                ]);
            }
        }

        return redirect()->route($role . '.jurnal')->with('success', 'Penilaian berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        // dd($request->all());
        $role = Auth::user()->role;

        $request->validate([
            'uraian' => 'required|string|max:255',
            'judul' => 'required|string|max:255',
            'komentar' => 'nullable|string|max:255',    
            'komentar_balasan' => 'nullable|string',
            'lampiran.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx,mp4|max:20480',
        ]);

        // Cari jurnal yang ingin diupdate
        $jurnal = Jurnal::find($id);

        if (!$jurnal) {
            return back()->with('error', 'Jurnal tidak ditemukan.');
        }

        // Cek apakah user yang login adalah admin atau karyawan yang terkait
        $karyawan = Karyawan::where('user_id', auth()->id())->first();
        if ($role !== 'admin' && $jurnal->karyawan_id !== ($karyawan ? $karyawan->id : null)) {
            return back()->with('error', 'Anda tidak memiliki akses untuk mengedit jurnal ini.');
        }

        // Update jurnal
        $jurnal->uraian = $request->uraian;
        $jurnal->judul = $request->judul;
        $jurnal->status = 'draft'; // Set status ke draft
        $jurnal->komentar = $request->komentar;
        $jurnal->komentar_balasan = $request->komentar_balasan;

        // Update lampiran jika ada
        if ($request->hasFile('lampiran')) {
            // Hapus lampiran lama jika perlu
            foreach ($jurnal->lampiran as $lampiran) {
                Storage::disk('public')->delete($lampiran->file_path);
                $lampiran->delete();
            }

            // Validasi jumlah lampiran
            $files = $request->file('lampiran');
            if (count($files) > 5) {
                return back()->with('error', 'Maksimal 5 lampiran diperbolehkan.');
            }

            // Upload lampiran baru
            foreach ($files as $file) {
                $path = $file->store('lampiran', 'public');
                $jurnal->lampiran()->create([
                    'file_path' => $path,
                    'file_type' => $this->getFileType($file),
                ]);
            }
        }

        // Simpan jurnal yang telah diperbarui
        $jurnal->save();

        return redirect()->route($role . '.jurnal')->with('success', 'Jurnal berhasil diperbarui.');
    }



    public function edit($id)
    {
        $jurnal = Jurnal::findOrFail($id);
        $role = Auth::user()->role;

        if ($role == 'admin') {
            $karyawan = Karyawan::with('user')->get();
            return view('admin.jurnal.edit', compact('jurnal', 'karyawan'));
        } else {
            return view('admin.jurnal.edit', compact('jurnal'));
        }
    }

    public function destroy($id)
    {
        $jurnal = Jurnal::findOrFail($id);
    
        // Hapus file lampiran kalau ada
        if ($jurnal->lampiran) {
            foreach ($jurnal->lampiran as $lampiran) {
                Storage::disk('public')->delete($lampiran->file_path);
                $lampiran->delete();
            }
        }
    
        // Simpan status sebelum dihapus
        $status = $jurnal->status;
    
        // Hapus jurnal
        $jurnal->delete();
    
        // Redirect sesuai status
        if ($status === 'disetujui' || $status === 'ditolak') {
            return redirect()->route('karyawan.jurnalL')->with('success', 'Jurnal berhasil dihapus.');
        }
    
        return redirect()->route('karyawan.jurnal')->with('success', 'Jurnal berhasil dihapus.');
    }
    


    public function uploadLampiran(Request $request, $id)
    {
        $request->validate([
            'lampiran.*' => 'file|mimes:jpg,jpeg,png,pdf,doc,docx,mp4|max:20480',
        ]);

        $jurnal = Jurnal::findOrFail($id);

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
        $jurnal = Jurnal::find($id);

        if (!$jurnal) {
            return redirect()->route($role . '.jurnal')->with('error', 'Jurnal tidak ditemukan.');
        }

        // Ambil lampiran yang terkait dengan jurnal
        $lampiran = $jurnal->lampiran;

        return view('admin.jurnal.lampiran', compact('jurnal', 'lampiran'));
    }

    public function approve($id)
{
    // Cari jurnal berdasarkan ID
    $jurnal = Jurnal::findOrFail($id);

    // Ubah status menjadi "approved"
    $jurnal->status = 'dikirim';
    $jurnal->save();

    // Redirect kembali dengan pesan sukses
    return redirect()->back()->with('success', 'Jurnal berhasil disetujui.');
}

public function approveAll()
{
    // Ubah semua jurnal yang status-nya bukan draft menjadi 'dikirim'
    Jurnal::where('status', '=', 'draft')->update(['status' => 'dikirim']);

    return redirect()->back()->with('success', 'Semua jurnal berhasil dikirim.');
}


}
