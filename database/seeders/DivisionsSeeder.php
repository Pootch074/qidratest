<?php

namespace Database\Seeders;

use App\Models\Office;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

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

        $officeIds = Office::pluck('id', 'office_name');

        if ($officeIds->isEmpty()) {
            $this->command->error('❌ No offices found. Please seed Divisions first.');

            return;
        }

        $now = Carbon::now();

        $divisions = [
            ['division_name' => 'OFFICE OF THE REGIONAL DIRECTOR', 'office_name' => 'DSWD FO XI'],
            ['division_name' => 'OFFICE OF THE ASSISTANT REGIONAL DIRECTOR FOR OPERATION', 'office_name' => 'DSWD FO XI'],
            ['division_name' => 'OFFICE OF THE ASSISTANT REGIONAL DIRECTOR FOR ADMINISTRATION', 'office_name' => 'DSWD FO XI'],
            ['division_name' => 'DISASTER RESPONSE MANAGEMENT DIVISION', 'office_name' => 'DSWD FO XI'],
            ['division_name' => 'FINANCIAL MANAGEMENT DIVISION', 'office_name' => 'DSWD FO XI'],
            ['division_name' => 'GENERAL ADMINISTRATIVE SUPPORT SERVICES DIVISION', 'office_name' => 'DSWD FO XI'],
            ['division_name' => 'HUMAN RESOURCE MANAGEMENT AND DEVELOPMENT DIVISION', 'office_name' => 'DSWD FO XI'],
            ['division_name' => 'INNOVATIONS DIVISION', 'office_name' => 'DSWD FO XI'],
            ['division_name' => 'PANTAWID PAMILYANG PILIPINO PROGRAM MANAGEMENT DIVISION', 'office_name' => 'DSWD FO XI'],
            ['division_name' => 'POLICY AND PLANS DIVISION', 'office_name' => 'DSWD FO XI'],
            ['division_name' => 'PROMOTIVE SERVICES DIVISION', 'office_name' => 'DSWD FO XI'],
            ['division_name' => 'PROTECTIVE SERVICES DIVISION', 'office_name' => 'DSWD FO XI'],
            ['division_name' => 'PROVINCIAL SOCIAL WELFARE AND DEVELOPMENT OFFICE', 'office_name' => 'DSWD FO XI'],
            ['division_name' => 'REGIONAL JUVENILE JUSTICE WELFARE COUNCIL', 'office_name' => 'DSWD FO XI'],
            ['division_name' => 'RESOURCE MANAGEMENT', 'office_name' => 'DSWD FO XI'],
            ['division_name' => 'POO DAVAO DEL SUR', 'office_name' => 'DSWD FO XI'],
            ['division_name' => 'POO DAVAO DEL NORTE', 'office_name' => 'DSWD FO XI'],
            ['division_name' => 'POO DAVAO OCCIDENTAL', 'office_name' => 'DSWD FO XI'],
            ['division_name' => 'POO DAVAO ORIENTAL', 'office_name' => 'DSWD FO XI'],
            ['division_name' => 'POO DAVAO DE ORO', 'office_name' => 'DSWD FO XI'],
        ];

        $divisions = array_map(function ($division) use ($officeIds, $now) {
            $officeId = $officeIds[$division['office_name']] ?? null;

            if (! $officeId) {
                throw new \Exception("❌ Office '{$division['office_name']}' not found. Please check spelling.");
            }

            return [
                'office_id' => $officeId,
                'division_name' => $division['division_name'],
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }, $divisions);

        DB::table('divisions')->insert($divisions);

        $this->command->info('✅ Divisions seeded successfully for office: DSWD FO XI');
    }
}
