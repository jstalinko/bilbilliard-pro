<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Administrator',
            'email' => 'admin@admin.com',
            'password' => bcrypt('password'),
            'email_verified_at' => now()
        ]);

        \App\Models\BilliardTable::factory(24)->create(); // 5 meja
         \App\Models\Product::factory(10)->create(); // 10 produk

        $pricing_rates = [
            [
                'name' => 'JAM-SIANG',
                'price_per_hour' => 25000,
                'start_at' => '10:00:00',
                'end_at' => '14:00:00'
            ],
            [
                'name' => 'JAM-SORE',
                'price_per_hour' => 30000,
                'start_at' => '14:01:00',
                'end_at' => '18:00:00'
            ],
            [
                'name' => 'JAM-MALAM',
                'price_per_hour' => 35000,
                'start_at' => '18:01:00',
                'end_at' => '21:00:00'
            ],
            [
                'name' => 'JAM-MIDNIGHT',
                'price_per_hour' => 40000,
                'start_at' => '21:01:00',
                'end_at' => '02:00:00'
            ]
        ];
        foreach($pricing_rates as $rate)
        {
            \App\Models\PricingRate::create($rate);
        }

        // $this->call([
        //     SessionAndTransactionSeeder::class,
        // ]);
    }
}
