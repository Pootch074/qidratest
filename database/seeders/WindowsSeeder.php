<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WindowsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('windows')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $windows = [
            [
                'window_number' => 1,
                'step_id' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'window_number' => 1,
                'step_id' => 2,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'window_number' => 1,
                'step_id' => 3,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'window_number' => 1,
                'step_id' => 4,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'window_number' => 1,
                'step_id' => 5,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'window_number' => 1,
                'step_id' => 6,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'window_number' => 1,
                'step_id' => 7,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'window_number' => 1,
                'step_id' => 8,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'window_number' => 1,
                'step_id' => 9,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'window_number' => 1,
                'step_id' => 10,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'window_number' => 1,
                'step_id' => 11,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'window_number' => 1,
                'step_id' => 12,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'window_number' => 1,
                'step_id' => 13,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],

            // CBSS Windows
            [
                'window_number' => 1,
                'step_id' => 14,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],

            // CIS Windows
            [
                'window_number' => 1,
                'step_id' => 15,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'window_number' => 1,
                'step_id' => 16,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'window_number' => 2,
                'step_id' => 16,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'window_number' => 1,
                'step_id' => 17,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'window_number' => 2,
                'step_id' => 17,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'window_number' => 3,
                'step_id' => 17,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'window_number' => 4,
                'step_id' => 17,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'window_number' => 1,
                'step_id' => 18,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'window_number' => 2,
                'step_id' => 18,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'window_number' => 3,
                'step_id' => 18,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];
        DB::table('windows')->insert($windows);
    }
}
