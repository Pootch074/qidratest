<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class OfficesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('offices')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $offices = [
            'field_office' => 'DSWD FO XI',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ];
        DB::table('offices')->insert($offices);
    }
}
