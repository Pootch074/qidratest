<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\QuestionnaireLevel;
use Carbon\Carbon;

class QuestionnaireLevelsSeeder extends Seeder
{
    public function run()

    {
        QuestionnaireLevel::query()->delete();

        $csvFile = fopen(base_path("database/data/questionnaire_level.csv"), "r");

        $firstline = true;
        while (($data = fgetcsv($csvFile, 2000, ",")) !== FALSE) {
            QuestionnaireLevel::create([
                    "id" => $data['0'],
                    "questionnaire_id" => $data['1'],
                    "level" => $data['2'],
                    "remarks" => $data['3'],
                    "created_at" => Carbon::parse($data['4'])->toDateTimeString(),
                    "updated_at" => Carbon::parse($data['5'])->toDateTimeString()
                ]);
        }
        fclose($csvFile);
    }
}
