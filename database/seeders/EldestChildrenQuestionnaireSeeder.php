<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EldestChildrenQuestionnaireSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // eldest children
        // DB::table('assessment_means')->delete();
        // DB::table('questionnaires')->delete();
        $eldest = [
            [
                'id' => 4,
                'reference_number' => '',
                'questionnaire_tree_id' => 1,
                'name' => 'Vision, Mision, Goals, and Organizational Structure',
                'description' => '',
                'weight' => 1,
                'user_id' => 1,
                'parent_id' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 5,
                'reference_number' => '',
                'questionnaire_tree_id' => 1,
                'name' => 'Human Resource Management and Development',
                'description' => '',
                'weight' => 2,
                'user_id' => 1,
                'parent_id' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 6,
                'reference_number' => '',
                'questionnaire_tree_id' => 1,
                'name' => 'Public Financial Management',
                'description' => '',
                'weight' => 3,
                'user_id' => 1,
                'parent_id' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 7,
                'reference_number' => '',
                'questionnaire_tree_id' => 1,
                'name' => 'Support Services',
                'description' => '',
                'weight' => 4,
                'user_id' => 1,
                'parent_id' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 8,
                'reference_number' => '',
                'questionnaire_tree_id' => 1,
                'name' => 'Planning',
                'description' => '',
                'weight' => 1,
                'user_id' => 1,
                'parent_id' => 2,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 9,
                'reference_number' => '',
                'questionnaire_tree_id' => 1,
                'name' => 'Implementation',
                'description' => '(Note: coordinate with LDRRMO to accomplish this indicator)',
                'weight' => 2,
                'user_id' => 1,
                'parent_id' => 2,
                'created_at' => now(),
                'updated_at' => now()
            ],

            [
                'id' => 10,
                'reference_number' => '',
                'questionnaire_tree_id' => 1,
                'name' => 'Monitoring and Reporting',
                'description' => '',
                'weight' => 4,
                'user_id' => 1,
                'parent_id' => 2,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 11,
                'reference_number' => '',
                'questionnaire_tree_id' => 1,
                'name' => 'Case Management',
                'description' => '',
                'weight' => 5,
                'user_id' => 1,
                'parent_id' => 2,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 12,
                'reference_number' => '',
                'questionnaire_tree_id' => 1,
                'name' => 'Residential Care and Community-Based Center',
                'description' => '',
                'weight' => 6,
                'user_id' => 1,
                'parent_id' => 2,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 13,
                'reference_number' => '',
                'questionnaire_tree_id' => 1,
                'name' => 'Functionality of Local Committee on Anti-Traffcking and Violence Against Women and their Children (LCAT-VAWC)',
                'description' => '',
                'weight' => 1,
                'user_id' => 1,
                'parent_id' => 3,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 14,
                'reference_number' => '',
                'questionnaire_tree_id' => 1,
                'name' => 'Functionality of Local Council for the Protection of Children',
                'description' => '',
                'weight' => 2,
                'user_id' => 1,
                'parent_id' => 3,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 15,
                'reference_number' => '',
                'questionnaire_tree_id' => 1,
                'name' => 'Inter-office Collaboration',
                'description' => '',
                'weight' => 3,
                'user_id' => 1,
                'parent_id' => 3,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 16,
                'reference_number' => '',
                'questionnaire_tree_id' => 1,
                'name' => 'Support to Civil Society Organizations',
                'description' => '',
                'weight' => 4,
                'user_id' => 1,
                'parent_id' => 3,
                'created_at' => now(),
                'updated_at' => now()
            ],
        ];
        DB::table('questionnaires')->insert($eldest);
    }
}
