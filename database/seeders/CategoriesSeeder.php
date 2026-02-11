<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriesSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('windows')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $categories = [
            [
                'step_id' => 15,
                'category_name' => 'priority',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'step_id' => 15,
                'category_name' => 'regular',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'step_id' => 16,
                'category_name' => 'priority',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'step_id' => 16,
                'category_name' => 'regular',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];
        DB::table('categories')->insert($categories);
    }
}
