<?php

namespace Database\Seeders;

use Illuminate\Support\Carbon;
use Illuminate\Database\Seeder;
use App\Models\KategoriPenilaian;

class KategoriPenilaianSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        KategoriPenilaian::insert([
            [
                'nama_kategori' => 'Ketepatan Waktu',
                'deskripsi' => 'Penilaian atas kedisiplinan waktu kerja',
                'tipe_penilaian' => 'objektif',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'nama_kategori' => 'Kualitas Pekerjaan',
                'deskripsi' => 'Menilai hasil kerja dari segi akurasi dan mutu',
                'tipe_penilaian' => 'subjektif',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'nama_kategori' => 'Kerja Sama Tim',
                'deskripsi' => 'Dinilai berdasarkan kolaborasi dengan tim',
                'tipe_penilaian' => 'subjektif',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'nama_kategori' => 'Jumlah Laporan',
                'deskripsi' => 'Berdasarkan total laporan yang dikumpulkan',
                'tipe_penilaian' => 'objektif',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'nama_kategori' => 'Jumlah Dokumen',
                'deskripsi' => 'Berdasarkan jumlah file dokumen dari jurnal harian yang disetujui',
                'tipe_penilaian' => 'objektif',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'nama_kategori' => 'Kesesuaian Waktu',
                'deskripsi' => 'Penilaian berdasarkan waktu pengumpulan laporan dibandingkan deadline target kinerja',
                'tipe_penilaian' => 'objektif',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'nama_kategori' => 'Kualitas Isi Laporan',
                'deskripsi' => 'Dinilai dari kelengkapan dan kejelasan isi pelaporan kinerja',
                'tipe_penilaian' => 'subjektif',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'nama_kategori' => 'Inisiatif',
                'deskripsi' => 'Penilaian terhadap karyawan dalam mengambil langkah proaktif terhadap tugas',
                'tipe_penilaian' => 'subjektif',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'nama_kategori' => 'Tanggung Jawab',
                'deskripsi' => 'Diukur dari tingkat kepatuhan terhadap tugas yang diberikan',
                'tipe_penilaian' => 'subjektif',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}
