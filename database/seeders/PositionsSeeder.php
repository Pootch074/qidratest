<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PositionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Disable foreign key checks to allow truncate
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('positions')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $now = Carbon::now();

        // List of positions
        $positions = [
            'Accountant III',
            'Administrative Aide I',
            'Administrative Aide II',
            'Administrative Aide III',
            'Administrative Aide IV',
            'Administrative Aide V',
            'Administrative Aide VI',
            'Administrative Assistant I',
            'Administrative Assistant II',
            'Administrative Assistant III',
            'Administrative Assistant III (Bookkeeper)',
            'Administrative Officer I',
            'Administrative Officer II',
            'Administrative Officer III',
            'Administrative Officer IV',
            'Administrative Officer V',
            'Area Coordinator',
            'Budget Assistant',
            'Cash Clerk',
            'Chief Administrative Officer',
            'Community Development Assistant II',
            'Community Development Officer II',
            'Community Development Officer III',
            'Community Development Officer IV',
            'Community Development Officer V',
            'Community Empowerment Facilitator',
            'Community Facilitator',
            'Community Facilitator Aide',
            'Encoder',
            'Executive Assistant',
            'Financial Analyst I',
            'Financial Analyst II',
            'Financial Analyst III',
            'Houseparent I',
            'Houseparent II',
            'Houseparent III',
            'Management and Audit Analyst II',
            'Manpower Development Officer I',
            'Manpower Development Officer II',
            'Medical Officer IV',
            'Monitoring & Evaluation Officer II',
            'Monitoring & Evaluation Officer III',
            'Municipal Monitor',
            'None',
            'Notifier',
            'Planning Officer I',
            'Planning Officer II',
            'Planning Officer III',
            'Planning Officer IV',
            'Procurement Officer',
            'Project Development Officer I',
            'Project Development Officer II',
            'Project Development Officer III',
            'Project Development Officer IV',
            'Project Development Officer V',
            'Project Evaluation Officer III',
            'Project Evaluation Officer IV',
            'Psychologist I',
            'Social Marketing Officer',
            'Social Welfare Aide',
            'Social Welfare Assistant',
            'Social Welfare Officer I',
            'Social Welfare Officer II',
            'Social Welfare Officer III',
            'Social Welfare Officer IV',
            'Social Welfare Officer V',
            'Statistician Aide',
            'Statistician II',
            'Supervising Administrative Officer',
            'Teacher (ECCD)',
            'Technical Facilitator',
            'Training Assistant',
            'Training Specialist I',
            'Training Specialist II',
            'Training Specialist III',
            'Training Specialist IV',
            'Validator',
        ];

        // Map to insertable array
        $positionsToInsert = array_map(fn($position) => [
            'position_name' => $position,
            'created_at' => $now,
            'updated_at' => $now,
        ], $positions);

        // Insert into database
        DB::table('positions')->insert($positionsToInsert);

        $this->command->info('✅ Positions seeded successfully.');
    }
}
