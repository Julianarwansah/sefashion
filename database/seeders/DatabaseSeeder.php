<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create(); // COMMENT BARIS INI

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]); // COMMENT BARIS INI JIKA ADA

        $this->call([
            CustomerSeeder::class,
            AdminSeeder::class,
            ShippingZoneSeeder::class,
            ShippingRateSeeder::class,
            ShippingZoneCitySeeder::class,
            // Tambahkan seeder lainnya di sini
        ]);
    }
}