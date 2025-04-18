<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TargetKinerja;
use App\Models\Karyawan;
use App\Models\Divisi;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class TargetKinerjaController extends Controller
{
    // Menampilkan semua target (sudah ada)
    public function index()
    {
        // dd(Auth::user());

        $user = Auth::user();

        // Admin: tampilkan semua
        if ($user->role === 'admin') {
            $targetKinerja = TargetKinerja::with(['karyawan.user', 'divisi', 'dibuatOleh'])
                ->orderBy('created_at', 'desc')
                ->get();

            return view('admin.target_kinerja.index', compact('targetKinerja'));
        }

        // Karyawan
        if ($user->role === 'karyawan') {
            $karyawan = $user->karyawan;

            if (!$karyawan) {
                return redirect()->back()->with('error', 'Anda belum terdaftar sebagai karyawan.');
            }

            $karyawanId = $karyawan->id;
            $divisiId = $karyawan->divisi_id;

            $targetKinerja = TargetKinerja::with(['karyawan.user', 'divisi', 'dibuatOleh'])
                ->where(function ($query) {
                    $query->whereNull('karyawan_id')->whereNull('divisi_id'); // Tugas umum
                })
                ->orWhere('karyawan_id', $karyawanId) // Tugas pribadi
                ->orWhere(function ($query) use ($divisiId) {
                    $query->whereNull('karyawan_id')->where('divisi_id', $divisiId); // Tugas per divisi
                })
                ->orderBy('created_at', 'desc')
                ->get();


            return view('admin.target_kinerja.index', compact('targetKinerja'));
        }

        return abort(403, 'Akses tidak diizinkan.');
    }



    // Tampilkan form create
    public function create()
    {
        $karyawan = Karyawan::with('user')->get();

        $divisi = Divisi::all();
        return view('admin.target_kinerja.create', compact('karyawan', 'divisi'));
    }

    // Simpan data baru
    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'karyawan_id' => 'nullable|exists:karyawan,id',
            'divisi_id' => 'nullable|exists:divisi,id',
            'periode' => 'required|string|max:255',
            'judul_target' => 'required|string|max:255',
            'target_laporan' => 'required|integer|min:1',
            'deadline' => 'required|date',
        ]);

        TargetKinerja::create([
            'karyawan_id' => $request->filled('karyawan_id') ? $request->karyawan_id : null,
            'divisi_id' => $request->filled('divisi_id') ? $request->divisi_id : null,
            'periode' => $request->periode,
            'judul_target' => $request->judul_target,
            'target_laporan' => $request->target_laporan,
            'deadline' => $request->deadline,
            'dibuat_oleh' => Auth::id(),
        ]);
        

        return redirect()->route('admin.penugasan')->with('success', 'Target kinerja berhasil ditambahkan.');
    }

    // Tampilkan detail target
    public function show($id)
    {
        $target = TargetKinerja::with(['karyawan.user', 'divisi', 'dibuatOleh'])->findOrFail($id);
        return view('admin.target_kinerja.show', compact('target'));
    }

    // Tampilkan form edit
    public function edit($id)
    {
        $penugasan = TargetKinerja::findOrFail($id);
        $karyawan = Karyawan::with('user')->get();
        $divisi = Divisi::all();
        return view('admin.target_kinerja.edit', compact('penugasan', 'karyawan', 'divisi'));
    }

    // Proses update
    public function update(Request $request, $id)
    {
        // dd($request->all());

        $request->validate([
            'karyawan_id' => 'nullable',
            'divisi_id' => 'nullable',
            'periode' => 'required|string|max:255',
            'judul_target' => 'required|string|max:255',
            'target_laporan' => 'required|integer|min:1',
            'deadline' => 'required|date',
            'dibuat_oleh' => 'required|exists:users,id',
        ]);

        $target = TargetKinerja::findOrFail($id);
        $target->update([
            'karyawan_id' => $request->karyawan_id,
            'divisi_id' => $request->divisi_id,
            'periode' => $request->periode,
            'judul_target' => $request->judul_target,
            'target_laporan' => $request->target_laporan,
            'deadline' => $request->deadline,
            'dibuat_oleh' => $request->dibuat_oleh,
        ]);

        return redirect()->route('admin.penugasan')->with('success', 'Target kinerja berhasil diperbarui.');
    }

    // Hapus target
    public function destroy($id)
    {
        $target = TargetKinerja::findOrFail($id);
        $target->delete();

        return redirect()->route('admin.penugasan')->with('success', 'Target kinerja berhasil dihapus.');
    }
}
