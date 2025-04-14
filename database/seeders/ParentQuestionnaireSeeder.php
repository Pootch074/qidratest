<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ParentQuestionnaireSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Parents
        $parents = [
            [
                'id' => 1,
                'reference_number' => '',
                'questionnaire_tree_id' => 1,
                'name' => 'Administration and Organization',
                'description' => '',
                'weight' => 1,
                'user_id' => 1,
                'parent_id' => 0,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 2,
                'reference_number' => '',
                'questionnaire_tree_id' => 1,
                'name' => 'Program Management',
                'description' => '',
                'weight' => 1,
                'user_id' => 1,
                'parent_id' => 0,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 3,
                'reference_number' => '',
                'questionnaire_tree_id' => 1,
                'name' => 'Institutional Mechanism',
                'description' => '',
                'weight' => 1,
                'user_id' => 1,
                'parent_id' => 0,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];

        DB::table('questionnaires')->insert($parents);
    }
}
