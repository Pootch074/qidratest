<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\QuestionnaireLevel;

class QuestionnaireLevelsSeeder extends Seeder
{
    
    

    public function run()

    {
        QuestionnaireLevel::query()->delete();

        $csvFile = fopen(base_path("database/data/questionnaire_level.csv"), "r");

        $firstline = true;
        while (($data = fgetcsv($csvFile, 2000, ",")) !== FALSE) {
            if (!$firstline) {
                QuestionnaireLevel::create([
                    "id" => $data['0'],
                    "questionnaire_id" => $data['1'],
                    "level" => $data['2'],
                    "remarks" => $data['3'],
                    "created_at" => $data['4'],
                    "updated_at" => $data['5']
                ]);    
            }
            $firstline = false;
        }
        fclose($csvFile);
    }
}
