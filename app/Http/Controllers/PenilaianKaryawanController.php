<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Jurnal;
use App\Models\Karyawan;
use Illuminate\Http\Request;
use App\Models\PelaporanKinerja;
use App\Models\KategoriPenilaian;
use App\Models\PenilaianKaryawan;
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
        $kategori = KategoriPenilaian::all();

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
            'kategori_id' => 'required|exists:kategori_penilaian,id',
            'nilai' => 'required|numeric|min:1|max:100',
            'komentar' => 'nullable|string|max:255',
        ]);

        PenilaianKaryawan::create([
            'karyawan_id' => $request->karyawan_id,
            'penilai_id' => auth()->id(), // ID user yang sedang login
            'kategori_id' => $request->kategori_id,
            'nilai' => $request->nilai,
            'komentar' => $request->komentar,
            'periode' => now()->format('Y-m'), // Periode penilaian 
        ]);

        return redirect()->route('tim_penilai.riwayat_penilaian')->with('success', 'Penilaian berhasil ditambahkan.');
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

}
