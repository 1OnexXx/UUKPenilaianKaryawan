<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'nama_lengkap' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('12345678'), 
            'role' => 'admin',
        ]);
    }
}
