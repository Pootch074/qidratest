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
        // let's clear all data first before adding
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('provinces')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $provinces = [
            ['name' => 'N/A (Highly Urbanized)'],
            ['name' => 'Davao del Norte'],
            ['name' => 'Davao del Sur'],
            ['name' => 'Davao de Oro'],
            ['name' => 'Davao Oriental'],
            ['name' => 'Davao Occidental']
        ];

        DB::table('provinces')->insert($provinces);
    }
}
