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
                'step_name' => 'None',
                'section_id' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'step_number' => 1,
                'step_name' => 'None',
                'section_id' => 2,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'step_number' => 1,
                'step_name' => 'None',
                'section_id' => 3,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'step_number' => 1,
                'step_name' => 'None',
                'section_id' => 4,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'step_number' => 1,
                'step_name' => 'None',
                'section_id' => 5,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'step_number' => 1,
                'step_name' => 'None',
                'section_id' => 6,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'step_number' => 1,
                'step_name' => 'None',
                'section_id' => 7,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'step_number' => 1,
                'step_name' => 'None',
                'section_id' => 8,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'step_number' => 1,
                'step_name' => 'None',
                'section_id' => 9,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'step_number' => 1,
                'step_name' => 'None',
                'section_id' => 10,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'step_number' => 1,
                'step_name' => 'None',
                'section_id' => 11,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'step_number' => 1,
                'step_name' => 'None',
                'section_id' => 12,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'step_number' => 1,
                'step_name' => 'None',
                'section_id' => 13,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            // CBSS Steps
            [
                'step_number' => 1,
                'step_name' => 'None',
                'section_id' => 14,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],

            // CIS Steps
            [
                'step_number' => 1,
                'step_name' => 'Pre-assessment',
                'section_id' => 15,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'step_number' => 2,
                'step_name' => 'Encoding',
                'section_id' => 15,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'step_number' => 3,
                'step_name' => 'Assessment',
                'section_id' => 15,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'step_number' => 4,
                'step_name' => 'Release',
                'section_id' => 15,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],



            [
                'step_number' => 1,
                'step_name' => 'None',
                'section_id' => 16,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'step_number' => 1,
                'step_name' => 'None',
                'section_id' => 17,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'step_number' => 1,
                'step_name' => 'None',
                'section_id' => 18,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
        ];

        DB::table('steps')->insert($steps);
    }
}
