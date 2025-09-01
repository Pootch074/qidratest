<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class TransactionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('transactions')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $transactions = [
            [
                'queue_number' => 1,
                'client_type' => 'regular',
                'step_id' => 1,
                'window_id' => null,
                'section_id' => 2,
                'queue_status' => 'waiting',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'queue_number' => 2,
                'client_type' => 'regular',
                'step_id' => 1,
                'window_id' => null,
                'section_id' => 2,
                'queue_status' => 'waiting',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'queue_number' => 3,
                'client_type' => 'regular',
                'step_id' => 1,
                'window_id' => null,
                'section_id' => 2,
                'queue_status' => 'waiting',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],

        ];



        DB::table('transactions')->insert($transactions);
    }
}
