<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::insert([
            [
                'nama_lengkap' => 'Admin Utama',
                'email' => 'admin@example.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'role' => 'admin',
                'created_at' => now(),
            ],
            [
                'nama_lengkap' => 'Penilai Tim',
                'email' => 'penilai@example.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'role' => 'tim_penilai',
                'created_at' => now(),
            ],
            [
                'nama_lengkap' => 'Kepala Divisi HRD',
                'email' => 'kepala@example.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'role' => 'kepala_sekolah',
                'created_at' => now(),
            ],
        ]);
    }
}
