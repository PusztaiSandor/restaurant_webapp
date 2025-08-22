<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class TableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('tables')->insert([
            [
                'table_code' => 'bj1',
                'location' => 'beltér',
                'position' => 'jobb',
                'capacity' => 4,
                'is_reservable' => true,
                'notes' => 'Ablak mellett',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'table_code' => 'tk2',
                'location' => 'terasz',
                'position' => 'közép',
                'capacity' => 6,
                'is_reservable' => true,
                'notes' => 'Napernyő alatt',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'table_code' => 'bb3',
                'location' => 'beltér',
                'position' => 'bal',
                'capacity' => 2,
                'is_reservable' => true,
                'notes' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'table_code' => 'tb4',
                'location' => 'terasz',
                'position' => 'bal',
                'capacity' => 4,
                'is_reservable' => true,
                'notes' => 'Közel a bejárathoz',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'table_code' => 'kk5',
                'location' => 'beltér',
                'position' => 'közép',
                'capacity' => 8,
                'is_reservable' => true,
                'notes' => 'Nagyobb társaságoknak',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
