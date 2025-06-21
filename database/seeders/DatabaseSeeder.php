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
$members = [
    [
        'full_name' => 'McAllister Setiawan',
        'phone' => '081234567890',
        'point' => 0,
        'total_tx' => 0,
    ],
    [
        'full_name' => 'Alya Nirmala',
        'phone' => '082156789012',
        'point' => 0,
        'total_tx' => 0,
    ],
    [
        'full_name' => 'Bagus Saputra',
        'phone' => '081387654321',
        'point' => 0,
        'total_tx' => 0,
    ],
    [
        'full_name' => 'Clarissa Wijaya',
        'phone' => '087811223344',
        'point' => 0,
        'total_tx' => 0,
    ],
    [
        'full_name' => 'Dimas Pratama',
        'phone' => '083812345678',
        'point' => 0,
        'total_tx' => 0,
    ],
    [
        'full_name' => 'Eka Lestari',
        'phone' => '089900112233',
        'point' => 0,
        'total_tx' => 0,
    ],
    [
        'full_name' => 'Fajar Nugroho',
        'phone' => '081298765432',
        'point' => 0,
        'total_tx' => 0,
    ],
    [
        'full_name' => 'Gita Arum',
        'phone' => '085712345678',
        'point' => 0,
        'total_tx' => 0,
    ],
    [
        'full_name' => 'Hari Kurniawan',
        'phone' => '082199887766',
        'point' => 0,
        'total_tx' => 0,
    ],
    [
        'full_name' => 'Intan Ramadhani',
        'phone' => '088877766655',
        'point' => 0,
        'total_tx' => 0,
    ],
];

foreach($members as $me)
{
    \App\Models\Member::create($me);
}
  
      
        
     $products = [
    [ 'name' => 'Es Teh',        'category' => 'minuman', 'stock' => 100, 'price' => 10000 ],
    [ 'name' => 'Kopi Hitam',    'category' => 'minuman', 'stock' => 80,  'price' => 15000 ],
    [ 'name' => 'Jus Jeruk',     'category' => 'minuman', 'stock' => 60,  'price' => 17000 ],
    [ 'name' => 'Air Mineral',   'category' => 'minuman', 'stock' => 200, 'price' => 5000  ],
    [ 'name' => 'Teh Tarik',     'category' => 'minuman', 'stock' => 70,  'price' => 12000 ],
    [ 'name' => 'Susu Cokelat',  'category' => 'minuman', 'stock' => 90,  'price' => 14000 ],
    [ 'name' => 'Es Kopi Susu',  'category' => 'minuman', 'stock' => 75,  'price' => 18000 ],
    [ 'name' => 'Cappuccino',    'category' => 'minuman', 'stock' => 50,  'price' => 20000 ],
    [ 'name' => 'Roti Bakar',    'category' => 'makanan', 'stock' => 40,  'price' => 22000 ],
    [ 'name' => 'Nasi Goreng',   'category' => 'makanan', 'stock' => 30,  'price' => 30000 ],
    [ 'name' => 'Mie Goreng',    'category' => 'makanan', 'stock' => 35,  'price' => 25000 ],
    [ 'name' => 'Sosis Bakar',   'category' => 'makanan', 'stock' => 50,  'price' => 15000 ],
    [ 'name' => 'Kentang Goreng','category' => 'makanan', 'stock' => 60,  'price' => 17000 ],
    [ 'name' => 'Burger',        'category' => 'makanan', 'stock' => 25,  'price' => 35000 ],
    [ 'name' => 'Spaghetti',     'category' => 'makanan', 'stock' => 20,  'price' => 28000 ],
    [ 'name' => 'Pisang Cokelat','category' => 'makanan', 'stock' => 30,  'price' => 18000 ],
];


foreach($products as $pr)
{
    \App\Models\Product::create($pr);
}

       $billiard_tables = [
    // RASSON (6 meja)
    [ 'name' => 'RASSON-1', 'number' => 1,  'status' => 'available' ],
    [ 'name' => 'RASSON-2', 'number' => 2,  'status' => 'available' ],
    [ 'name' => 'RASSON-3', 'number' => 3,  'status' => 'available' ],
    [ 'name' => 'RASSON-4', 'number' => 4,  'status' => 'available' ],
    [ 'name' => 'RASSON-5', 'number' => 5,  'status' => 'available' ],
    [ 'name' => 'RASSON-6', 'number' => 6,  'status' => 'available' ],

    // AILLEX (5 meja)
    [ 'name' => 'AILLEX-1', 'number' => 7,  'status' => 'available' ],
    [ 'name' => 'AILLEX-2', 'number' => 8,  'status' => 'available' ],
    [ 'name' => 'AILLEX-3', 'number' => 9,  'status' => 'available' ],
    [ 'name' => 'AILLEX-4', 'number' => 10, 'status' => 'available' ],
    [ 'name' => 'AILLEX-5', 'number' => 11, 'status' => 'available' ],

    // PREDATOR (5 meja)
    [ 'name' => 'PREDATOR-1', 'number' => 12, 'status' => 'available' ],
    [ 'name' => 'PREDATOR-2', 'number' => 13, 'status' => 'available' ],
    [ 'name' => 'PREDATOR-3', 'number' => 14, 'status' => 'available' ],
    [ 'name' => 'PREDATOR-4', 'number' => 15, 'status' => 'available' ],
    [ 'name' => 'PREDATOR-5', 'number' => 16, 'status' => 'available' ],
];
foreach($billiard_tables as $billiard)
{
    \App\Models\BilliardTable::create($billiard);
}

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
