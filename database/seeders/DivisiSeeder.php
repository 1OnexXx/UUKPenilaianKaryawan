<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Divisi;

class DivisiSeeder extends Seeder
{
    public function run(): void
    {
        Divisi::insert([
            [
                'nama_divisi' => 'Human Resources',
                'deskripsi' => 'Divisi yang menangani kepegawaian',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_divisi' => 'Keuangan',
                'deskripsi' => 'Divisi yang mengelola laporan keuangan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_divisi' => 'IT Support',
                'deskripsi' => 'Divisi untuk infrastruktur dan teknologi',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
