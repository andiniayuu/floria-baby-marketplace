<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserAddress;
use Illuminate\Database\Seeder;

class UserAddressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil user dengan role 'user'
        $user = User::where('role', 'user')->first();

        if (!$user) {
            $this->command->warn('No user found with role "user". Please create a user first.');
            return;
        }

        // Sample addresses
        $addresses = [
            [
                'user_id' => $user->id,
                'label' => 'Rumah',
                'recipient_name' => $user->name,
                'phone' => '081234567890',
                'province' => 'Jawa Timur',
                'city' => 'Surabaya',
                'district' => 'Gubeng',
                'subdistrict' => 'Airlangga',
                'postal_code' => '60286',
                'full_address' => 'Jl. Airlangga No. 123, RT 05 RW 03',
                'notes' => 'Rumah pagar hijau, sebelah warung Pak Haji',
                'is_primary' => true,
            ],
            [
                'user_id' => $user->id,
                'label' => 'Kantor',
                'recipient_name' => $user->name,
                'phone' => '081234567891',
                'province' => 'Jawa Timur',
                'city' => 'Surabaya',
                'district' => 'Tegalsari',
                'subdistrict' => 'Kedungdoro',
                'postal_code' => '60261',
                'full_address' => 'Jl. HR Muhammad No. 456, Lantai 5',
                'notes' => 'Gedung biru, sebelah bank',
                'is_primary' => false,
            ],
            [
                'user_id' => $user->id,
                'label' => 'Kos',
                'recipient_name' => $user->name,
                'phone' => '081234567892',
                'province' => 'Jawa Timur',
                'city' => 'Surabaya',
                'district' => 'Mulyorejo',
                'subdistrict' => 'Mulyorejo',
                'postal_code' => '60115',
                'full_address' => 'Jl. Raya Mulyorejo No. 789, Kamar 12',
                'notes' => 'Kos putri, depan kampus',
                'is_primary' => false,
            ],
        ];

        foreach ($addresses as $address) {
            UserAddress::create($address);
        }

        $this->command->info('User addresses seeded successfully!');
    }
}