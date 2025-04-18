<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KategoriPenilaian;

class KategoriPenilaianController extends Controller
{
    public function index(){
        // Ambil semua data kategori penilaian dari database
        $kate = KategoriPenilaian::get();
        return view('admin.kategori_penilaian.index' , compact('kate'));
    }
    public function create(){
        return view('admin.kategori_penilaian.create');
    }
    public function store(Request $request){
        // Validasi data
        $request->validate([
            'nama_kategori' => 'required|string|max:255',
            'deskripsi' => 'nullable|string|max:1000',
            'tipe_penilaian' => 'required|string|max:255',
        ]);

        // Simpan data ke database
        KategoriPenilaian::create($request->all());

        return redirect()->route('admin.kategori_penilaian')->with('success', 'Kategori Penilaian berhasil ditambahkan.');
    }
    public function edit($id){
        $kate = KategoriPenilaian::findOrFail($id);
        return view('admin.kategori_penilaian.edit', compact('kate'));
    }
    public function update(Request $request, $id){
        // Validasi data
        $request->validate([
            'nama_kategori' => 'required|string|max:255',
            'deskripsi' => 'nullable|string|max:1000',
            'tipe_penilaian' => 'required|string|max:255',
        ]);

        // Update data ke database
        $kate = KategoriPenilaian::findOrFail($id);
        $kate->update($request->all());

        return redirect()->route('admin.kategori_penilaian')->with('success', 'Kategori Penilaian berhasil diperbarui.');
    }
    public function destroy($id){
        // Hapus data dari database
        $kate = KategoriPenilaian::findOrFail($id);
        $kate->delete();

        return redirect()->route('admin.kategori_penilaian')->with('success', 'Kategori Penilaian berhasil dihapus.');
    }
}
