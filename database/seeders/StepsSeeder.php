<?php

namespace Database\Seeders;

use App\Models\Section;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StepsSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('steps')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $now = Carbon::now();

        $sectionIds = Section::pluck('id', 'section_name');

        if ($sectionIds->isEmpty()) {
            $this->command->error('❌ No sections found. Please seed Sections first.');

            return;
        }

        $stepsData = [
            ['section_name' => 'DISASTER RESPONSE MANAGEMENT SECTION', 'step_number' => 1, 'step_name' => 'None'],
            ['section_name' => 'ACCOUNTING SECTION', 'step_number' => 1, 'step_name' => 'None'],
            ['section_name' => 'PROPERTY AND SUPPLY SECTION', 'step_number' => 1, 'step_name' => 'None'],
            ['section_name' => 'RECORDS AND ARCHIVE MANAGEMENT SECTION', 'step_number' => 1, 'step_name' => 'None'],
            ['section_name' => 'PERSONNEL ADMINISTRATION SECTION', 'step_number' => 1, 'step_name' => 'None'],
            ['section_name' => 'LEGAL UNIT', 'step_number' => 1, 'step_name' => 'None'],
            ['section_name' => 'SOCIAL MARKETING UNIT', 'step_number' => 1, 'step_name' => 'None'],
            ['section_name' => 'SOCIAL TECHNOLOGY UNIT', 'step_number' => 1, 'step_name' => 'None'],
            ['section_name' => 'NATIONAL HOUSEHOLD TARGETING SECTION', 'step_number' => 1, 'step_name' => 'None'],
            ['section_name' => 'POLICY DEVELOPMENT AND PLANNING SECTION', 'step_number' => 1, 'step_name' => 'None'],
            ['section_name' => 'STANDARDS SECTION', 'step_number' => 1, 'step_name' => 'None'],
            ['section_name' => 'SUSTAINABLE LIVELIHOOD PROGRAM', 'step_number' => 1, 'step_name' => 'None'],
            ['section_name' => 'CENTER AND RESIDENTIAL CARE FACILITY', 'step_number' => 1, 'step_name' => 'None'],
            ['section_name' => 'COMMUNITY-BASED SERVICES SECTION', 'step_number' => 1, 'step_name' => 'None'],

            ['section_name' => 'CRISIS INTERVENTION SECTION', 'step_number' => 1, 'step_name' => 'Pre-assessment'],
            ['section_name' => 'CRISIS INTERVENTION SECTION', 'step_number' => 2, 'step_name' => 'Encode'],
            ['section_name' => 'CRISIS INTERVENTION SECTION', 'step_number' => 3, 'step_name' => 'Assessment'],
            ['section_name' => 'CRISIS INTERVENTION SECTION', 'step_number' => 4, 'step_name' => 'Release'],

            ['section_name' => 'SOCIAL PENSION PROGRAM', 'step_number' => 1, 'step_name' => 'None'],
            ['section_name' => 'SUPPLEMENTARY FEEDING PROGRAM', 'step_number' => 1, 'step_name' => 'None'],
            ['section_name' => 'PANTAWID PAMILYA PILIPINO PROGRAM MANAGEMENT SECTION', 'step_number' => 1, 'step_name' => 'None'],
        ];

        $steps = array_map(function ($step) use ($sectionIds, $now) {
            $sectionId = $sectionIds[$step['section_name']] ?? null;

            if (! $sectionId) {
                throw new \Exception("❌ Section '{$step['section_name']}' not found. Please check spelling.");
            }

            return [
                'step_number' => $step['step_number'],
                'step_name' => $step['step_name'],
                'section_id' => $sectionId,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }, $stepsData);

        DB::table('steps')->insert($steps);

        $this->command->info('✅ Steps seeded successfully using dynamic section references.');
    }
}
