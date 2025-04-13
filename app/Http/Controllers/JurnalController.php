<?php

namespace App\Http\Controllers;

use App\Models\Jurnal;
use App\Models\Karyawan;
use Illuminate\Http\Request;

class JurnalController extends Controller
{
    public function index()
    {
        $userId = auth()->id(); // ambil ID user yang sedang login

        $jurnal = Jurnal::whereHas('karyawan', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })->with(['karyawan.user'])->get();

        return view('admin.jurnal.index', compact('jurnal'));
    }

    public function create()
    {
        return view('admin.jurnal.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'uraian' => 'required|string|max:255',
        ]);
    
        // Cari karyawan berdasarkan user yang sedang login
        $karyawan = Karyawan::where('user_id', auth()->id())->first();
    
        if (!$karyawan) {
            return redirect()->back()->with('error', 'Data karyawan tidak ditemukan untuk user ini.');
        }
    
        $jurnal = new Jurnal();
        $jurnal->karyawan_id = $karyawan->id;
        $jurnal->tanggal = now(); // atau $request->tanggal kalau kamu ada input tanggal
        $jurnal->uraian = $request->uraian;
        $jurnal->save();
    
        return redirect()->route('karyawan.jurnal')->with('success', 'Jurnal berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $jurnal = Jurnal::findOrFail($id);
        return view('admin.jurnal.edit', compact('jurnal'));
    }

    public function update(Request $request, $id)
{
    $request->validate([
        'uraian' => 'required|string|max:255',
    ]);

    // Cari karyawan yang sedang login
    $karyawan = Karyawan::where('user_id', auth()->id())->first();

    if (!$karyawan) {
        return redirect()->back()->with('error', 'Data karyawan tidak ditemukan.');
    }

    // Ambil jurnal yang akan diupdate dan pastikan milik karyawan ini
    $jurnal = Jurnal::where('id', $id)
        ->where('karyawan_id', $karyawan->id)
        ->first();

    if (!$jurnal) {
        return redirect()->back()->with('error', 'Jurnal tidak ditemukan atau bukan milik Anda.');
    }

    // Update data
    $jurnal->uraian = $request->uraian;
    $jurnal->save();

    return redirect()->route('karyawan.jurnal')->with('success', 'Jurnal berhasil diperbarui.');
}
    public function destroy($id)
    {
        $jurnal = Jurnal::findOrFail($id);
        $jurnal->delete();
        return redirect()->route('karyawan.jurnal')->with('success', 'Jurnal berhasil dihapus.');
    }
    
}
