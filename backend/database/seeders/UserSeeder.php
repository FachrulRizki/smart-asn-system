<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Buat User Admin
        User::create([
            'username' => 'admin',
            'email' => 'admin@asn.go.id',
            'password' => Hash::make('password123'),
        ]);

        // Buat User Pegawai Contoh
        User::create([
            'username' => 'fachrul',
            'email' => 'fachrul@asn.go.id',
            'password' => Hash::make('password123'),
        ]);
    }
}
