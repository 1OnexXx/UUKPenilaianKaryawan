<?php

namespace App\Http\Controllers;

use App\Models\Divisi;
use Illuminate\Http\Request;

class DivisiController extends Controller
{
    public function index(){
        // Ambil semua data divisi dari database
        $divisi = Divisi::get();
        return view('admin.divisi.index' , compact('divisi'));
    }
    public function create(){
        return view('admin.divisi.create');
    }
    public function store(Request $request){
        // Validasi data
        $request->validate([
            'nama_divisi' => 'required|string|max:255',
            'deskripsi' => 'nullable|string|max:1000',
        ]);

        // Simpan data ke database
        Divisi::create($request->all());

        return redirect()->route('admin.divisi')->with('success', 'Divisi berhasil ditambahkan.');
    }
    public function edit($id){
        $divisi = Divisi::findOrFail($id);
        return view('admin.divisi.edit', compact('divisi'));
    }
    public function update(Request $request, $id){
        // Validasi data
        $request->validate([
            'nama_divisi' => 'required|string|max:255',
            'deskripsi' => 'nullable|string|max:1000',
        ]);

        // Update data ke database
        $divisi = Divisi::findOrFail($id);
        $divisi->update($request->all());

        return redirect()->route('admin.divisi')->with('success', 'Divisi berhasil diperbarui.');
    }
    public function destroy($id){
        // Hapus data dari database
        $divisi = Divisi::findOrFail($id);
        $divisi->delete();

        return redirect()->route('admin.divisi')->with('success', 'Divisi berhasil dihapus.');
    }
}
