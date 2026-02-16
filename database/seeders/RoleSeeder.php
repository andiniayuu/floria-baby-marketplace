<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        // Role::firstOrCreate(['name' => 'seller', 'guard_name' => 'web']);
        // Role::firstOrCreate(['name' => 'user', 'guard_name' => 'web']);
        // // Admin
        User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // // Seller (Approved)
        User::create([
            'name' => 'Seller Demo',
            'email' => 'seller@example.com',
            'password' => Hash::make('password'),
            'role' => 'seller',
            'seller_status' => 'approved',
            'shop_name' => 'Toko Demo',
            'shop_description' => 'Ini adalah toko demo untuk testing',
        ]);

        // // User Biasa
        User::create([
            'name' => 'User Demo',
            'email' => 'user@example.com',
            'password' => Hash::make('password'),
            'role' => 'user',
        ]);
    }
}
