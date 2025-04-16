<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DivisiController;
use App\Http\Controllers\JurnalController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LaporanPenilaianController;
use App\Http\Controllers\PelaporanKinerjaController;
use App\Http\Controllers\KategoriPenilaianController;
use App\Http\Controllers\PenilaianKaryawanController;

Route::get('/', function () {
    return view('tes');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');


Route::prefix('admin')->middleware(['auth', 'role:admin'])->group(function () {

    Route::get('/divisi', [DivisiController::class, 'index'])->name('admin.divisi');
    Route::get('/divisi/create', [DivisiController::class, 'create'])->name('admin.divisi.create');
    Route::post('/divisi/store', [DivisiController::class, 'store'])->name('admin.divisi.store');
    Route::get('/divisi/edit/{id}', [DivisiController::class, 'edit'])->name('admin.divisi.edit');
    Route::put('/divisi/update/{id}', [DivisiController::class, 'update'])->name('admin.divisi.update');
    Route::delete('/divisi/delete/{id}', [DivisiController::class, 'destroy'])->name('admin.divisi.delete');

    Route::get('/kategori_penilaian', [KategoriPenilaianController::class, 'index'])->name('admin.kategori_penilaian');
    Route::get('/kategori_penilaian/create', [KategoriPenilaianController::class, 'create'])->name('admin.kategori_penilaian.create');
    Route::post('/kategori_penilaian/store', [KategoriPenilaianController::class, 'store'])->name('admin.kategori_penilaian.store');
    Route::get('/kategori_penilaian/edit/{id}', [KategoriPenilaianController::class, 'edit'])->name('admin.kategori_penilaian.edit');
    Route::put('/kategori_penilaian/update/{id}', [KategoriPenilaianController::class, 'update'])->name('admin.kategori_penilaian.update');
    Route::delete('/kategori_penilaian/delete/{id}', [KategoriPenilaianController::class, 'destroy'])->name('admin.kategori_penilaian.delete');

    Route::get('/manajemen_pengguna', [UserController::class, 'index'])->name('admin.manajemen_pengguna');
    Route::get('/manajemen_pengguna/create', [UserController::class, 'create'])->name('admin.manajemen_pengguna.create');
    Route::post('/manajemen_pengguna/store', [UserController::class, 'store'])->name('admin.manajemen_pengguna.store');
    Route::get('/manajemen_pengguna/edit/{id}', [UserController::class, 'edit'])->name('admin.manajemen_pengguna.edit');
    Route::put('/manajemen_pengguna/update/{id}', [UserController::class, 'update'])->name('admin.manajemen_pengguna.update');
    Route::delete('/manajemen_pengguna/delete/{id}', [UserController::class, 'destroy'])->name('admin.manajemen_pengguna.delete');

    Route::get('/karyawan', [KaryawanController::class, 'index'])->name('admin.karyawan');
    Route::get('/karyawan/edit/{id}', [KaryawanController::class, 'edit'])->name('admin.karyawan.edit');
    Route::put('/karyawan/update/{id}', [KaryawanController::class, 'update'])->name('admin.karyawan.update');
    Route::delete('/karyawan/delete/{id}', [KaryawanController::class, 'destroy'])->name('admin.karyawan.delete');
    Route::get('/karyawan/show/{id}', [KaryawanController::class, 'show'])->name('admin.karyawan.show');

    Route::get('/jurnal', [JurnalController::class, 'index'])->name('admin.jurnal');
    Route::get('/jurnal/create', [JurnalController::class, 'create'])->name('admin.jurnal.create');
    Route::post('/jurnal/store', [JurnalController::class, 'store'])->name('admin.jurnal.store');
    Route::get('/jurnal/edit/{id}', [JurnalController::class, 'edit'])->name('admin.jurnal.edit');
    Route::put('/jurnal/update/{id}', [JurnalController::class, 'update'])->name('admin.jurnal.update');
    Route::delete('/jurnal/delete/{id}', [JurnalController::class, 'destroy'])->name('admin.jurnal.delete');

    Route::get('/laporan_penilaian', [LaporanPenilaianController::class, 'index'])->name('admin.laporan_penilaian');
    Route::get('/laporan_penilaian/create', [LaporanPenilaianController::class, 'create'])->name('admin.laporan_penilaian.create');
    Route::post('/laporan_penilaian/store', [LaporanPenilaianController::class, 'store'])->name('admin.laporan_penilaian.store');
    Route::get('/laporan_penilaian/edit/{id}', [LaporanPenilaianController::class, 'edit'])->name('admin.laporan_penilaian.edit');
    Route::put('/laporan_penilaian/update/{id}', [LaporanPenilaianController::class, 'update'])->name('admin.laporan_penilaian.update');
    Route::delete('/laporan_penilaian/delete/{id}', [LaporanPenilaianController::class, 'destroy'])->name('admin.laporan_penilaian.delete');
});

Route::prefix('karyawan')->middleware(['auth', 'role:karyawan'])->group(function () {

    Route::get('/jurnal', [JurnalController::class, 'index'])->name('karyawan.jurnal');
    Route::get('/jurnal/create', [JurnalController::class, 'create'])->name('karyawan.jurnal.create');
    Route::post('/jurnal/store', [JurnalController::class, 'store'])->name('karyawan.jurnal.store');
    Route::get('/jurnal/edit/{id}', [JurnalController::class, 'edit'])->name('karyawan.jurnal.edit');
    Route::put('/jurnal/update/{id}', [JurnalController::class, 'update'])->name('karyawan.jurnal.update');
    Route::delete('/jurnal/delete/{id}', [JurnalController::class, 'destroy'])->name('karyawan.jurnal.delete');

    Route::get('/pelaporan', [PelaporanKinerjaController::class, 'index'])->name('karyawan.pelaporan');
    Route::get('/pelaporan/create', [PelaporanKinerjaController::class, 'create'])->name('karyawan.pelaporan.create');
    Route::post('/pelaporan/store', [PelaporanKinerjaController::class, 'store'])->name('karyawan.pelaporan.store');
    Route::get('/pelaporan/edit/{id}', [PelaporanKinerjaController::class, 'edit'])->name('karyawan.pelaporan.edit');
    Route::put('/pelaporan/update/{id}', [PelaporanKinerjaController::class, 'update'])->name('karyawan.pelaporan.update');
    Route::delete('/pelaporan/delete/{id}', [PelaporanKinerjaController::class, 'destroy'])->name('karyawan.pelaporan.delete');

    Route::get('/riwayat_penilaian', [PenilaianKaryawanController::class, 'index'])->name('karyawan.riwayat_penilaian');
});


Route::prefix('tim_penilai')->middleware(['auth', 'role:tim_penilai'])->group(function () {
    Route::get('/kategori_penilaian', [KategoriPenilaianController::class, 'index'])->name('tim_penilai.kategori_penilaian');
    Route::get('/karyawan', [KaryawanController::class, 'index'])->name('tim_penilai.karyawan');

    Route::get('/riwayat_penilaian', [PenilaianKaryawanController::class, 'index'])->name('tim_penilai.riwayat_penilaian');
    Route::get('/penilaian/create/{karyawan_id}', [PenilaianKaryawanController::class, 'create'])->name('penilaian.create');
    Route::post('/riwayat_penilaian/store', [PenilaianKaryawanController::class, 'store'])->name('tim_penilai.riwayat_penilaian.store');
    Route::get('/riwayat_penilaian/edit/{id}', [PenilaianKaryawanController::class, 'edit'])->name('tim_penilai.riwayat_penilaian.edit');
    Route::put('/riwayat_penilaian/update/{id}', [PenilaianKaryawanController::class, 'update'])->name('tim_penilai.riwayat_penilaian.update');
    Route::delete('/riwayat_penilaian/delete/{id}', [PenilaianKaryawanController::class, 'destroy'])->name('tim_penilai.riwayat_penilaian.delete');

    Route::get('/jurnal/show/{id}', [PenilaianKaryawanController::class, 'showJ'])->name('tim_penilai.jurnal.show'); // ganti 'tim_penilai' sesuai role

    Route::get('/laporan/show/{id}', [PenilaianKaryawanController::class, 'showL'])->name('tim_penilai.laporan.show');
});


Route::prefix('kepala_sekolah')->middleware(['auth', 'role:kepala_sekolah'])->group(function () {

    Route::get('/laporan_penilaian', [LaporanPenilaianController::class, 'index'])->name('kepala_sekolah.laporan_penilaian');
    Route::get('/laporan_penilaian/create', [LaporanPenilaianController::class, 'create'])->name('kepala_sekolah.laporan_penilaian.create');
    Route::post('/laporan_penilaian/store', [LaporanPenilaianController::class, 'store'])->name('kepala_sekolah.laporan_penilaian.store');
    Route::get('/laporan_penilaian/edit/{id}', [LaporanPenilaianController::class, 'edit'])->name('kepala_sekolah.laporan_penilaian.edit');
    Route::put('/laporan_penilaian/update/{id}', [LaporanPenilaianController::class, 'update'])->name('kepala_sekolah.laporan_penilaian.update');
    Route::delete('/laporan_penilaian/delete/{id}', [LaporanPenilaianController::class, 'destroy'])->name('kepala_sekolah.laporan_penilaian.delete');

    Route::get('/pelaporan', [PelaporanKinerjaController::class, 'index'])->name('kepala_sekolah.pelaporan');
    Route::get('/pelaporan/review/{id}', [PelaporanKinerjaController::class, 'show'])->name('kepala_sekolah.pelaporan.review');
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
// web.php
Route::get('/laporan', [LaporanController::class, 'generateLaporan'])->name('laporan.generate');
