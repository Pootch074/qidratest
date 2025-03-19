<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProvincesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $provinces = [
            ['name' => 'N/A (Highly Urbanized)'],
            ['name' => 'Davao del Norte'],
            ['name' => 'Davao del Sur'],
            ['name' => 'Davao Oriental'],
            ['name' => 'Davao Occidental']
        ];

        DB::table('provinces')->insert($provinces);
    }
}
