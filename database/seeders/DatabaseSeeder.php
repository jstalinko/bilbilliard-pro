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

    // \App\Models\BilliardTable::factory(5)->create(); // 5 meja
    // \App\Models\Product::factory(10)->create(); // 10 produk

    // $this->call([
    //     SessionAndTransactionSeeder::class,
    // ]);
    }
}
