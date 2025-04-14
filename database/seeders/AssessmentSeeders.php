<?php

namespace Database\Seeders;

use App\Models\Questionnaire;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AssessmentSeeders extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // let's clear all data first before adding
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('questionnaire_trees')->truncate();
        DB::table('questionnaires')->truncate();
        DB::table('means_of_verifications')->truncate();
        DB::table('questionnaire_levels')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Questionnaire tree
        $questionnaireTree = [
            [
                'id' => 1,
                'questionnaire_name' => 'SDCA 2025 Questionnaire',
                'effectivity_date' => '2025-06-01',
                'status' => 'unpublished',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];
        DB::table('questionnaire_trees')->insert($questionnaireTree);

        $this->call(ParentQuestionnaireSeeder::class);
        $this->call(EldestChildrenQuestionnaireSeeder::class);
        $this->call(MiddleChildrenQuestionnaireSeeder::class);
        $this->call(MeansOfVerificationSeeder::class);
        $this->call(QuestionnaireLevelsSeeder::class);

    }
}
