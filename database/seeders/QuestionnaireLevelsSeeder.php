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
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        QuestionnaireLevel::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $csvFile = fopen(base_path("database/data/questionnaire_level.csv"), "r");

        $firstline = true;
        while (($data = fgetcsv($csvFile, 2000, ",")) !== FALSE) {
            QuestionnaireLevel::create([
                "id" => $data['0'],
                "questionnaire_id" => $data['1'],
                "level" => $data['2'],
                "remarks" => $data['3'],
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now()
            ]);
        }
        fclose($csvFile);
    }
}
