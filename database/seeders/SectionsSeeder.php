<?php

namespace Database\Seeders;

use App\Models\Division;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SectionsSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('sections')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $divisionIds = Division::pluck('id', 'division_name');

        if ($divisionIds->isEmpty()) {
            $this->command->error('❌ No divisions found. Please seed Divisions first.');

            return;
        }

        $now = Carbon::now();

        $sections = [
            ['section_name' => 'OFFICE OF THE REGIONAL DIRECTOR', 'division_name' => 'OFFICE OF THE REGIONAL DIRECTOR'],
            ['section_name' => 'OFFICE OF THE ASSISTANT REGIONAL DIRECTOR FOR OPERATIONS', 'division_name' => 'OFFICE OF THE REGIONAL DIRECTOR'],
            ['section_name' => 'OFFICE OF THE ASSISTANT REGIONAL DIRECTOR FOR ADMINISTRATION', 'division_name' => 'OFFICE OF THE REGIONAL DIRECTOR'],
            ['section_name' => 'ANTI-RED TAPE UNIT', 'division_name' => 'OFFICE OF THE REGIONAL DIRECTOR'],
            ['section_name' => 'INTERNAL AUDIT UNIT', 'division_name' => 'OFFICE OF THE REGIONAL DIRECTOR'],
            ['section_name' => 'LEGAL UNIT', 'division_name' => 'OFFICE OF THE REGIONAL DIRECTOR'],
            ['section_name' => 'REGIONAL JUVENILE JUSTICE AND WELFARE COUNCIL', 'division_name' => 'OFFICE OF THE REGIONAL DIRECTOR'],
            ['section_name' => 'REGIONAL SUB-COMMITTEE FOR THE WELFARE OF CHILDREN', 'division_name' => 'OFFICE OF THE REGIONAL DIRECTOR'],
            ['section_name' => 'SECRETARY OF THE DIRECTOR', 'division_name' => 'OFFICE OF THE REGIONAL DIRECTOR'],
            ['section_name' => 'SOCIAL MARKETING UNIT', 'division_name' => 'OFFICE OF THE REGIONAL DIRECTOR'],

            ['section_name' => 'DISASTER RESPONSE MANAGEMENT SECTION', 'division_name' => 'DISASTER RESPONSE MANAGEMENT DIVISION'],
            ['section_name' => 'DISASTER RESPONSE AND REHABILITATION SECTION', 'division_name' => 'DISASTER RESPONSE MANAGEMENT DIVISION'],
            ['section_name' => 'REGIONAL RESOURCE OPERATIONS SECTION', 'division_name' => 'DISASTER RESPONSE MANAGEMENT DIVISION'],
            ['section_name' => 'DISASTER RESPONSE INFORMATION MANAGEMENT SECTION', 'division_name' => 'DISASTER RESPONSE MANAGEMENT DIVISION'],
            
            ['section_name' => 'ACCOUNTING SECTION', 'division_name' => 'FINANCIAL MANAGEMENT DIVISION'],
            ['section_name' => 'BUDGET SECTION', 'division_name' => 'FINANCIAL MANAGEMENT DIVISION'],
            ['section_name' => 'CASH SECTION', 'division_name' => 'FINANCIAL MANAGEMENT DIVISION'],

            ['section_name' => 'PROPERTY AND SUPPLY SECTION', 'division_name' => 'GENERAL ADMINISTRATIVE SUPPORT SERVICES DIVISION'],
            ['section_name' => 'RECORDS AND ARCHIVE MANAGEMENT SECTION', 'division_name' => 'GENERAL ADMINISTRATIVE SUPPORT SERVICES DIVISION'],

            ['section_name' => 'PERSONNEL ADMINISTRATION SECTION', 'division_name' => 'HUMAN RESOURCE MANAGEMENT AND DEVELOPMENT DIVISION'],
            ['section_name' => 'HUMAN RESOURCE PLANNING AND PERFORMANCE MANAGEMENT SECTION', 'division_name' => 'HUMAN RESOURCE MANAGEMENT AND DEVELOPMENT DIVISION'],
            ['section_name' => 'LEARNING DEVELOPMENT SECTION', 'division_name' => 'HUMAN RESOURCE MANAGEMENT AND DEVELOPMENT DIVISION'],
            ['section_name' => 'WELFARE SECTION', 'division_name' => 'HUMAN RESOURCE MANAGEMENT AND DEVELOPMENT DIVISION'],

            ['section_name' => 'SOCIAL TECHNOLOGY UNIT', 'division_name' => 'INNOVATIONS DIVISION'],
            ['section_name' => 'PAG-ABOT', 'division_name' => 'INNOVATIONS DIVISION'],

            ['section_name' => 'PANTAWID PAMILYA PILIPINO PROGRAM MANAGEMENT SECTION', 'division_name' => 'PANTAWID PAMILYANG PILIPINO PROGRAM MANAGEMENT DIVISION'],
            ['section_name' => '4PS CITY/MUNICIPAL OPERATIONS OFFICE', 'division_name' => 'PANTAWID PAMILYANG PILIPINO PROGRAM MANAGEMENT DIVISION'],
            ['section_name' => '4PS PROVINCIAL OPERATIONS OFFICE', 'division_name' => 'PANTAWID PAMILYANG PILIPINO PROGRAM MANAGEMENT DIVISION'],
            ['section_name' => '4PS REGIONAL MANAGEMENT OFFICE', 'division_name' => 'PANTAWID PAMILYANG PILIPINO PROGRAM MANAGEMENT DIVISION'],
            ['section_name' => '4PS PROVINCIAL SOCIAL WELFARE AND DEVELOPMENT OFFICE', 'division_name' => 'PANTAWID PAMILYANG PILIPINO PROGRAM MANAGEMENT DIVISION'],
            ['section_name' => '4PS DAVAO CITY', 'division_name' => 'PANTAWID PAMILYANG PILIPINO PROGRAM MANAGEMENT DIVISION'],
            ['section_name' => '4PS DAVAO DEL NORTE', 'division_name' => 'PANTAWID PAMILYANG PILIPINO PROGRAM MANAGEMENT DIVISION'],
            ['section_name' => '4PS DAVAO DE ORO', 'division_name' => 'PANTAWID PAMILYANG PILIPINO PROGRAM MANAGEMENT DIVISION'],
            ['section_name' => '4PS DAVAO DEL SUR', 'division_name' => 'PANTAWID PAMILYANG PILIPINO PROGRAM MANAGEMENT DIVISION'],
            ['section_name' => '4PS DAVAO OCCIDENTAL', 'division_name' => 'PANTAWID PAMILYANG PILIPINO PROGRAM MANAGEMENT DIVISION'],
            ['section_name' => '4PS DAVAO ORIENTAL', 'division_name' => 'PANTAWID PAMILYANG PILIPINO PROGRAM MANAGEMENT DIVISION'],
            
            ['section_name' => 'BENEFICIARY FIRST', 'division_name' => 'POLICY AND PLANS DIVISION'],
            ['section_name' => 'INFORMATION AND COMMUNICATIONS TECHNOLOGY MANAGEMENT SECTION', 'division_name' => 'POLICY AND PLANS DIVISION'],
            ['section_name' => 'NATIONAL HOUSEHOLD TARGETING SECTION', 'division_name' => 'POLICY AND PLANS DIVISION'],
            ['section_name' => 'POLICY DEVELOPMENT AND PLANNING SECTION', 'division_name' => 'POLICY AND PLANS DIVISION'],
            ['section_name' => 'STANDARDS SECTION', 'division_name' => 'POLICY AND PLANS DIVISION'],
            ['section_name' => 'TARGETED CASH TRANSFER PROGRAM MANAGEMENT OFFICE', 'division_name' => 'POLICY AND PLANS DIVISION'],
            ['section_name' => 'TECHNICAL ADVISORY ASSISTANCE AND OTHER RELATED SUPPORT SERVICES', 'division_name' => 'POLICY AND PLANS DIVISION'],
            ['section_name' => 'UNCONDITIONAL CASH TRANSFER PROGRAM MANAGEMENT OFFICE', 'division_name' => 'POLICY AND PLANS DIVISION'],
            
            ['section_name' => 'SUSTAINABLE LIVELIHOOD PROGRAM', 'division_name' => 'PROMOTIVE SERVICES DIVISION'],
            ['section_name' => 'KALAHI CIDSS PROGRAM MANAGEMENT OFFICE', 'division_name' => 'PROMOTIVE SERVICES DIVISION'],
            
            ['section_name' => 'CAPABILITY BUILDING SECTION', 'division_name' => 'PROTECTIVE SERVICES DIVISION'],
            ['section_name' => 'CENTER AND RESIDENTIAL CARE FACILITY', 'division_name' => 'PROTECTIVE SERVICES DIVISION'],
            ['section_name' => 'CENTER BASED SERVICES', 'division_name' => 'PROTECTIVE SERVICES DIVISION'],
            ['section_name' => 'CENTER FOR CHILDREN WITH SPECIAL NEEDS', 'division_name' => 'PROTECTIVE SERVICES DIVISION'],
            ['section_name' => 'COMMUNITY-BASED SERVICES SECTION', 'division_name' => 'PROTECTIVE SERVICES DIVISION'],
            ['section_name' => 'CRISIS INTERVENTION SECTION', 'division_name' => 'PROTECTIVE SERVICES DIVISION'],
            ['section_name' => 'HOME FOR GIRLS AND WOMEN', 'division_name' => 'PROTECTIVE SERVICES DIVISION'],
            ['section_name' => 'HOME FOR THE AGED', 'division_name' => 'PROTECTIVE SERVICES DIVISION'],
            ['section_name' => 'RECEPTION AND STUDY CENTER FOR CHILDREN', 'division_name' => 'PROTECTIVE SERVICES DIVISION'],
            ['section_name' => 'REGIONAL ALTERNATIVE CHILD CARE OFFICE', 'division_name' => 'PROTECTIVE SERVICES DIVISION'],
            ['section_name' => 'REGIONAL REHABILITATION CENTER FOR YOUTH', 'division_name' => 'PROTECTIVE SERVICES DIVISION'],
            ['section_name' => 'SOCIAL PENSION PROGRAM', 'division_name' => 'PROTECTIVE SERVICES DIVISION'],
            ['section_name' => 'SUPPLEMENTARY FEEDING PROGRAM', 'division_name' => 'PROTECTIVE SERVICES DIVISION'],
            ['section_name' => 'TECHNICAL ASSISTANCE AND RESOURCE AUGMENTATION', 'division_name' => 'PROTECTIVE SERVICES DIVISION'],

            ['section_name' => 'Provincial Social Welfare and Development Office', 'division_name' => 'PROVINCIAL SOCIAL WELFARE AND DEVELOPMENT OFFICE'],
            
            ['section_name' => 'SWAD DIGOS', 'division_name' => 'POO DAVAO DEL SUR'],
            ['section_name' => 'SWAD TAGUM', 'division_name' => 'POO DAVAO DEL NORTE'],
            ['section_name' => 'SWAD MALITA', 'division_name' => 'POO DAVAO OCCIDENTAL'],
            ['section_name' => 'SWAD MATI', 'division_name' => 'POO DAVAO ORIENTAL'],
            ['section_name' => 'SWAD NABUNTURAN', 'division_name' => 'POO DAVAO DE ORO'],
        ];

        $sections = array_map(function ($section) use ($divisionIds, $now) {
            $divisionId = $divisionIds[$section['division_name']] ?? null;

            if (! $divisionId) {
                throw new \Exception("❌ Division '{$section['division_name']}' not found. Please check spelling.");
            }

            return [
                'division_id' => $divisionId,
                'section_name' => $section['section_name'],
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }, $sections);

        DB::table('sections')->insert($sections);

        $this->command->info('✅ Sections seeded successfully with dynamic division references.');
    }
}
