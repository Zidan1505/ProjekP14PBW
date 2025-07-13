<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DummyUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@gmail.com'], // Mencari pengguna dengan email ini
            [
                'name' => 'admin',       // Data yang diisi jika pengguna tidak ditemukan
                'password' => Hash::make('admin123'),
            ]
        );
    }
}