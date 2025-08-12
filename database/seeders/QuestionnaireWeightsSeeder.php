<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class QuestionnaireWeightsSeeder extends Seeder
{
    public function run(): void
    {
        // DB::table('questionnaire_weights')->truncate();

        DB::table('questionnaire_weights')->insert([
            ['questionnaire_id' => 4, 'weight' => 0.070, 'created_at' => '2025-07-28 03:09:02', 'updated_at' => '2025-07-28 03:09:02'],
            ['questionnaire_id' => 5, 'weight' => 0.110, 'created_at' => '2025-07-28 03:10:36', 'updated_at' => '2025-07-28 03:10:36'],
            ['questionnaire_id' => 6, 'weight' => 0.090, 'created_at' => '2025-07-28 03:10:36', 'updated_at' => '2025-07-28 03:10:36'],
            ['questionnaire_id' => 7, 'weight' => 0.080, 'created_at' => '2025-07-28 03:10:36', 'updated_at' => '2025-07-28 03:10:36'],
            ['questionnaire_id' => 8, 'weight' => 0.160, 'created_at' => '2025-07-28 03:10:36', 'updated_at' => '2025-07-28 03:10:36'],
            ['questionnaire_id' => 9, 'weight' => 0.090, 'created_at' => '2025-07-28 03:10:36', 'updated_at' => '2025-07-28 03:10:36'],
            ['questionnaire_id' => 10, 'weight' => 0.070, 'created_at' => '2025-07-28 03:10:36', 'updated_at' => '2025-07-28 03:10:36'],
            ['questionnaire_id' => 11, 'weight' => 0.130, 'created_at' => '2025-07-28 03:10:36', 'updated_at' => '2025-07-28 03:10:36'],
            ['questionnaire_id' => 12, 'weight' => 0.000, 'created_at' => '2025-07-28 03:10:36', 'updated_at' => '2025-07-28 03:10:36'],
            ['questionnaire_id' => 13, 'weight' => 0.050, 'created_at' => '2025-07-28 03:10:36', 'updated_at' => '2025-07-28 03:10:36'],
            ['questionnaire_id' => 14, 'weight' => 0.060, 'created_at' => '2025-07-28 03:10:36', 'updated_at' => '2025-07-28 03:10:36'],
            ['questionnaire_id' => 15, 'weight' => 0.040, 'created_at' => '2025-07-28 03:10:36', 'updated_at' => '2025-07-28 03:10:36'],
            ['questionnaire_id' => 16, 'weight' => 0.050, 'created_at' => '2025-07-28 03:10:36', 'updated_at' => '2025-07-28 03:10:36'],
        ]);
    }
}
