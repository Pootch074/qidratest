<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MeansOfVerificationOthersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */    
    public function run(): void
    {
        DB::statement('SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO"');

        DB::statement("
            INSERT INTO means_of_verifications (id, questionnaire_id, means, created_at, updated_at)
            VALUES (0, 0, 'Others', NOW(), NOW())
            ON DUPLICATE KEY UPDATE
                questionnaire_id = VALUES(questionnaire_id),
                means = VALUES(means),
                updated_at = NOW()
        ");
    }
}
