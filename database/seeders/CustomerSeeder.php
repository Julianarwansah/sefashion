<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customers = [
            // Customer dengan alamat lengkap di Jakarta
            [
                'nama' => 'Ahmad Wijaya',
                'email' => 'ahmad.wijaya@email.com',
                'password' => Hash::make('password123'),
                'no_hp' => '081234567890',
                'alamat' => 'Jl. Sudirman No. 123',
                'province_id' => '6', // DKI Jakarta
                'city_id' => '152', // Jakarta Selatan
                'province_name' => 'DKI Jakarta',
                'city_name' => 'Jakarta Selatan',
                'kode_pos' => '12190',
            ],
            [
                'nama' => 'Siti Rahayu',
                'email' => 'siti.rahayu@email.com',
                'password' => Hash::make('password123'),
                'no_hp' => '081234567891',
                'alamat' => 'Jl. Thamrin No. 45',
                'province_id' => '6', // DKI Jakarta
                'city_id' => '153', // Jakarta Pusat
                'province_name' => 'DKI Jakarta',
                'city_name' => 'Jakarta Pusat',
                'kode_pos' => '10110',
            ],
            [
                'nama' => 'Budi Santoso',
                'email' => 'budi.santoso@email.com',
                'password' => Hash::make('password123'),
                'no_hp' => '081234567892',
                'alamat' => 'Jl. Mangga Dua Raya No. 8',
                'province_id' => '6', // DKI Jakarta
                'city_id' => '151', // Jakarta Utara
                'province_name' => 'DKI Jakarta',
                'city_name' => 'Jakarta Utara',
                'kode_pos' => '14450',
            ],

            // Customer dengan alamat lengkap di Jawa Barat
            [
                'nama' => 'Dewi Lestari',
                'email' => 'dewi.lestari@email.com',
                'password' => Hash::make('password123'),
                'no_hp' => '081234567893',
                'alamat' => 'Jl. Asia Afrika No. 65',
                'province_id' => '9', // Jawa Barat
                'city_id' => '23', // Bandung
                'province_name' => 'Jawa Barat',
                'city_name' => 'Kota Bandung',
                'kode_pos' => '40111',
            ],
            [
                'nama' => 'Rizki Pratama',
                'email' => 'rizki.pratama@email.com',
                'password' => Hash::make('password123'),
                'no_hp' => '081234567894',
                'alamat' => 'Jl. Merdeka No. 12',
                'province_id' => '9', // Jawa Barat
                'city_id' => '22', // Bekasi
                'province_name' => 'Jawa Barat',
                'city_name' => 'Kota Bekasi',
                'kode_pos' => '17141',
            ],
            [
                'nama' => 'Maya Sari',
                'email' => 'maya.sari@email.com',
                'password' => Hash::make('password123'),
                'no_hp' => '081234567895',
                'alamat' => 'Jl. Peta Selatan No. 88',
                'province_id' => '9', // Jawa Barat
                'city_id' => '78', // Cimahi
                'province_name' => 'Jawa Barat',
                'city_name' => 'Kota Cimahi',
                'kode_pos' => '40531',
            ],

            // Customer dengan alamat lengkap di Jawa Tengah
            [
                'nama' => 'Joko Susilo',
                'email' => 'joko.susilo@email.com',
                'password' => Hash::make('password123'),
                'no_hp' => '081234567896',
                'alamat' => 'Jl. Pemuda No. 34',
                'province_id' => '10', // Jawa Tengah
                'city_id' => '399', // Semarang
                'province_name' => 'Jawa Tengah',
                'city_name' => 'Kota Semarang',
                'kode_pos' => '50132',
            ],
            [
                'nama' => 'Ani Wulandari',
                'email' => 'ani.wulandari@email.com',
                'password' => Hash::make('password123'),
                'no_hp' => '081234567897',
                'alamat' => 'Jl. Solo No. 56',
                'province_id' => '10', // Jawa Tengah
                'city_id' => '255', // Surakarta (Solo)
                'province_name' => 'Jawa Tengah',
                'city_name' => 'Kota Surakarta',
                'kode_pos' => '57141',
            ],

            // Customer dengan alamat lengkap di Jawa Timur
            [
                'nama' => 'Hendra Gunawan',
                'email' => 'hendra.gunawan@email.com',
                'password' => Hash::make('password123'),
                'no_hp' => '081234567898',
                'alamat' => 'Jl. Tunjungan No. 78',
                'province_id' => '11', // Jawa Timur
                'city_id' => '444', // Surabaya
                'province_name' => 'Jawa Timur',
                'city_name' => 'Kota Surabaya',
                'kode_pos' => '60275',
            ],
            [
                'nama' => 'Linda Permata',
                'email' => 'linda.permata@email.com',
                'password' => Hash::make('password123'),
                'no_hp' => '081234567899',
                'alamat' => 'Jl. Basuki Rahmat No. 90',
                'province_id' => '11', // Jawa Timur
                'city_id' => '29', // Malang
                'province_name' => 'Jawa Timur',
                'city_name' => 'Kota Malang',
                'kode_pos' => '65112',
            ],

            // Customer dengan alamat lengkap di Bali
            [
                'nama' => 'Putu Aditya',
                'email' => 'putu.aditya@email.com',
                'password' => Hash::make('password123'),
                'no_hp' => '081234567800',
                'alamat' => 'Jl. Legian No. 123',
                'province_id' => '1', // Bali
                'city_id' => '17', // Badung
                'province_name' => 'Bali',
                'city_name' => 'Kabupaten Badung',
                'kode_pos' => '80351',
            ],
            [
                'nama' => 'Komang Surya',
                'email' => 'komang.surya@email.com',
                'password' => Hash::make('password123'),
                'no_hp' => '081234567801',
                'alamat' => 'Jl. Raya Ubud No. 45',
                'province_id' => '1', // Bali
                'city_id' => '114', // Gianyar
                'province_name' => 'Bali',
                'city_name' => 'Kabupaten Gianyar',
                'kode_pos' => '80511',
            ],

            // Customer dengan alamat lengkap di Sumatera Utara
            [
                'nama' => 'Rudi Hartono',
                'email' => 'rudi.hartono@email.com',
                'password' => Hash::make('password123'),
                'no_hp' => '081234567802',
                'alamat' => 'Jl. Sisingamangaraja No. 67',
                'province_id' => '34', // Sumatera Utara
                'city_id' => '457', // Medan
                'province_name' => 'Sumatera Utara',
                'city_name' => 'Kota Medan',
                'kode_pos' => '20154',
            ],

            // Customer dengan alamat lengkap di Sulawesi Selatan
            [
                'nama' => 'Andi Saputra',
                'email' => 'andi.saputra@email.com',
                'password' => Hash::make('password123'),
                'no_hp' => '081234567803',
                'alamat' => 'Jl. Pettarani No. 89',
                'province_id' => '38', // Sulawesi Selatan
                'city_id' => '65', // Makassar
                'province_name' => 'Sulawesi Selatan',
                'city_name' => 'Kota Makassar',
                'kode_pos' => '90231',
            ],

            // Customer TANPA alamat lengkap (untuk testing validasi)
            [
                'nama' => 'Test Customer Incomplete',
                'email' => 'test.incomplete@email.com',
                'password' => Hash::make('password123'),
                'no_hp' => '081234567804',
                'alamat' => 'Jl. Test No. 1',
                'province_id' => null,
                'city_id' => null,
                'province_name' => null,
                'city_name' => null,
                'kode_pos' => null,
            ],
        ];

        foreach ($customers as $customer) {
            Customer::create($customer);
        }

        $this->command->info('Successfully created ' . count($customers) . ' customers with complete addresses for testing.');
    }
}