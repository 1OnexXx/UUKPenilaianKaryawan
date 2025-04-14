<?php

namespace App\Http\Controllers;

use App\Models\Jurnal;
use App\Models\Karyawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JurnalController extends Controller
{
    public function index()
    {
        $userId = auth()->id(); // ambil ID user yang sedang login
        $role = Auth::user()->role;

        if ($role == 'karyawan') {
            $jurnal = Jurnal::whereHas('karyawan', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })->with(['karyawan.user'])->get();
        } else {
            $jurnal = Jurnal::all();
        }

        return view('admin.jurnal.index', compact('jurnal'));
    }

    public function create()
    {
        $role = Auth::user()->role;

        if ($role == 'admin') {
            $karyawan = Karyawan::with('user')->get();
            return view('admin.jurnal.create', compact('karyawan'));
        } else {
            return view('admin.jurnal.create');
        }
    }

    public function store(Request $request)
    {
        $role = Auth::user()->role;
        $request->validate([
            'uraian' => 'required|string|max:255',

        ]);

        // Cari karyawan berdasarkan user yang sedang login
        $karyawan = Karyawan::where('user_id', auth()->id())->first();

        if (!$karyawan) {

            $jurnal = new Jurnal();
            $jurnal->karyawan_id = $request->karyawan_id;
            $jurnal->tanggal = now(); // atau $request->tanggal kalau kamu ada input tanggal
            $jurnal->uraian = $request->uraian;
            $jurnal->save();
        } else {
            $jurnal = new Jurnal();
            $jurnal->karyawan_id = $karyawan->id;
            $jurnal->tanggal = now(); // atau $request->tanggal kalau kamu ada input tanggal
            $jurnal->uraian = $request->uraian;
            $jurnal->save();
        }



        return redirect()->route($role . '.jurnal')->with('success', 'Jurnal berhasil ditambahkan.');
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

    public function update(Request $request, $id)
    {
        $request->validate([
            'uraian' => 'required|string|max:255',
        ]);

        $user = auth()->user();

        // Ambil jurnal berdasarkan role
        if ($user->role === 'admin') {
            $jurnal = Jurnal::find($id); // Admin bisa ambil jurnal mana pun
        } else {
            // Untuk karyawan: pastikan hanya bisa edit miliknya sendiri
            $karyawan = Karyawan::where('user_id', $user->id)->first();
            if (!$karyawan) {
                return redirect()->back()->with('error', 'Data karyawan tidak ditemukan.');
            }

            $jurnal = Jurnal::where('id', $id)
                ->where('karyawan_id', $karyawan->id)
                ->first();
        }

        if (!$jurnal) {
            return redirect()->back()->with('error', 'Jurnal tidak ditemukan atau Anda tidak punya akses.');
        }

        // Update data
        $jurnal->uraian = $request->uraian;
        $jurnal->save();

        return redirect()->route($user->role . '.jurnal')->with('success', 'Jurnal berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $jurnal = Jurnal::findOrFail($id);
        $jurnal->delete();
        return redirect()->route('karyawan.jurnal')->with('success', 'Jurnal berhasil dihapus.');
    }
}
