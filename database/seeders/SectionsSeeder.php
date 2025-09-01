<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class SectionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('sections')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $sections = [
            [
                'section_name' => 'Crisis Intervention Section',
                'division_id' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
               'section_name' => 'Community-Based Services Section',
                'division_id' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now() 
            ],
        ];
        DB::table('sections')->insert($sections);
    }
}
