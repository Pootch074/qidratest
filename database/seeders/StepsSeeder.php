<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StepsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('steps')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $steps = [
            [
                'step_number' => 1,
                'step_name' => 'preassess',
                'section_id' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'step_number' => 2,
                'step_name' => 'encode',
                'section_id' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'step_number' => 3,
                'step_name' => 'assessment',
                'section_id' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'step_number' => 4,
                'step_name' => 'release',
                'section_id' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'step_number' => 1,
                'step_name' => 'None',
                'section_id' => 14,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'step_number' => 2,
                'step_name' => 'None',
                'section_id' => 14,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'step_number' => 3,
                'step_name' => 'None',
                'section_id' => 14,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
        ];

        DB::table('steps')->insert($steps);
    }
}
