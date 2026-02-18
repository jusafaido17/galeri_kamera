<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buat akun Admin
        User::create([
            'name' => 'Admin Sewa Kamera',
            'email' => 'admin@sewakamera.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin'
        ]);

        // Buat akun User biasa untuk testing
        // Buat akun User biasa untuk testing
        User::create([
         'name' => 'User Demo',
         'email' => 'user@example.com',
         'password' => Hash::make('user123'),
         'role' => 'user',
         'member_level' => 'bronze',
         'total_completed_orders' => 0
        ]);
    }
}
