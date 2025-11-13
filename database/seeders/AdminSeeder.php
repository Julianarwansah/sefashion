<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admins = [
            [
                'nama' => 'Administrator Utama',
                'email' => 'admin@example.com',
                'password' => Hash::make('password123'),
                'no_hp' => '081234567890',
                'alamat' => 'Jl. Contoh Alamat No. 123, Jakarta',
            ]
        ];

        foreach ($admins as $admin) {
            Admin::create($admin);
        }

        $this->command->info('Seeder Admin berhasil ditambahkan!');
    }
}