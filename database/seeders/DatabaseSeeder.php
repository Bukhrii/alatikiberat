<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
{
    // Akun Admin Gudang
    User::create([
        'username' => 'admin_gudang',
        'password' => Hash::make('password123'),
        'real_name' => 'Budi Setiawan',
        'role' => 'warehouse_admin',
    ]);

    // Akun Manajer Pembelian
    User::create([
        'username' => 'manajer_pembelian',
        'password' => Hash::make('password123'),
        'real_name' => 'Bambang Wijaya',
        'role' => 'procurement_manager',
    ]);
}
}
