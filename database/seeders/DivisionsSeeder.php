<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DivisionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('divisions')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $divisions = [
            'division_name' => 'Protective Services Division',
            'office_id' => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ];
        DB::table('divisions')->insert($divisions);
    }
}
