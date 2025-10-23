<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Division;

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

        $now = Carbon::now();

        $divisionIds = Division::pluck('id', 'division_name');

        if ($divisionIds->isEmpty()) {
            $this->command->error('❌ No divisions found. Please seed Divisions first.');
            return;
        }

        $sections = [
            ['section_name' => 'DISASTER RESPONSE MANAGEMENT SECTION', 'division_name' => 'Disaster Response Management Division'],
            ['section_name' => 'ACCOUNTING SECTION', 'division_name' => 'Financial Management Division'],
            ['section_name' => 'PROPERTY AND SUPPLY SECTION', 'division_name' => 'GENERAL ADMINISTRATIVE SUPPORT SERVICES DIVISION'],
            ['section_name' => 'RECORDS AND ARCHIVE MANAGEMENT SECTION', 'division_name' => 'GENERAL ADMINISTRATIVE SUPPORT SERVICES DIVISION'],
            ['section_name' => 'HR PERSONNEL ADMINISTRATION SECTION (HRPASS)', 'division_name' => 'Human Resource Management and Development Division'],
            ['section_name' => 'LEGAL UNIT', 'division_name' => 'Office of the Regional Director'],
            ['section_name' => 'SOCIAL MARKETING UNIT', 'division_name' => 'Office of the Regional Director'],
            ['section_name' => 'SOCIAL TECHNOLOGY UNIT', 'division_name' => 'Office of the Regional Director'],
            ['section_name' => 'NATIONAL HOUSEHOLD TARGETING SECTION', 'division_name' => 'Policy and Plans Division'],
            ['section_name' => 'POLICY DEVELOPMENT AND PLANNING SECTION', 'division_name' => 'Policy and Plans Division'],
            ['section_name' => 'STANDARDS SECTION', 'division_name' => 'Policy and Plans Division'],
            ['section_name' => 'SUSTAINABLE LIVELIHOOD PROGRAM (SLP)', 'division_name' => 'Promotive Services Division'],
            ['section_name' => 'CENTER AND RESIDENTIAL CARE FACILITY (CRCF)', 'division_name' => 'Protective Services Division'],
            ['section_name' => 'COMMUNITY-BASED SERVICES SECTION (CBSS)', 'division_name' => 'Protective Services Division'],
            ['section_name' => 'CRISIS INTERVENTION SECTION', 'division_name' => 'Protective Services Division'],
            ['section_name' => 'SOCIAL PENSION PROGRAM', 'division_name' => 'Protective Services Division'],
            ['section_name' => 'SUPPLEMENTARY FEEDING PROGRAM', 'division_name' => 'Protective Services Division'],
            ['section_name' => 'PANTAWID PAMILYA PILIPINO PROGRAM MANAGEMENT SECTION', 'division_name' => 'Pantawid Pamilyang Pilipino Program Management Division'],
        ];

        $sections = array_map(function ($section) use ($divisionIds, $now) {
            $divisionId = $divisionIds[$section['division_name']] ?? null;

            if (! $divisionId) {
                throw new \Exception("❌ Division '{$section['division_name']}' not found. Please check spelling.");
            }

            return [
                'section_name' => $section['section_name'],
                'division_id' => $divisionId,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }, $sections);

        DB::table('sections')->insert($sections);

        $this->command->info('✅ Sections seeded successfully with dynamic division references.');
    }
}
