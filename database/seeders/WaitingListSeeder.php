<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\WaitingList;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class WaitingListSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        $data = [
            [
                'name' => 'Rizky',
                'status' => 'waiting',
                'number' =>1,
                'created_at' => Carbon::now()->subMinutes(10),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Siti',
                'status' => 'waiting',
                'number' =>2,
                'created_at' => Carbon::now()->subMinutes(5),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Dimas',
                'status' => 'played',
                'number' => 3,
                'created_at' => Carbon::now()->subHour(),
                'updated_at' => Carbon::now()->subMinutes(30),
            ],
            [
                'name' => 'Alya',
                'status' => 'cancel',
                'number' => 4,
                'created_at' => Carbon::now()->subHours(2),
                'updated_at' => Carbon::now()->subHours(1),
            ],
        ];

        WaitingList::insert($data);
    }
}
