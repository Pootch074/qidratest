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
                'section_name' => 'DISASTER RESPONSE MANAGEMENT SECTION',
                'division_id' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'section_name' => 'ACCOUNTING SECTION',
                'division_id' => 3,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'section_name' => 'PROPERTY AND SUPPLY SECTION',
                'division_id' => 2,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'section_name' => 'RECORDS AND ARCHIVE MANAGEMENT SECTION',
                'division_id' => 2,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'section_name' => 'HR PERSONNEL ADMINISTRATION SECTION (HRPASS)',
                'division_id' => 4,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'section_name' => 'LEGAL UNIT',
                'division_id' => 6,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'section_name' => 'SOCIAL MARKETING UNIT',
                'division_id' => 6,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'section_name' => 'SOCIAL TECHNOLOGY UNIT',
                'division_id' => 6,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'section_name' => 'NATIONAL HOUSEHOLD TARGETING SECTION',
                'division_id' => 8,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'section_name' => 'POLICY DEVELOPMENT AND PLANNING SECTION',
                'division_id' => 8,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'section_name' => 'STANDARDS SECTION',
                'division_id' => 8,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'section_name' => 'SUSTAINABLE LIVELIHOOD PROGRAM (SLP)',
                'division_id' => 9,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'section_name' => 'CENTER AND RESIDENTIAL CARE FACILITY (CRCF)',
                'division_id' => 10,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'section_name' => 'COMMUNITY-BASED SERVICES SECTION (CBSS)',
                'division_id' => 10,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'section_name' => 'CRISIS INTERVENTION SECTION',
                'division_id' => 10,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'section_name' => 'SOCIAL PENSION PROGRAM',
                'division_id' => 10,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'section_name' => 'SUPPLEMENTARY FEEDING PROGRAM',
                'division_id' => 10,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'section_name' => 'PANTAWID PAMILYA PILIPINO PROGRAM MANAGEMENT SECTION',
                'division_id' => 7,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
        ];
        DB::table('sections')->insert($sections);
    }
}
