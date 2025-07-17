<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\MeansOfVerification;

class MeansOfVerificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        MeansOfVerification::query()->delete();

        $csvFile = fopen(base_path("database/data/questionnaire_means.csv"), "r");

        while (($data = fgetcsv($csvFile, 2000, ",")) !== FALSE) {
        MeansOfVerification::create([
            "id" => $data[0] ?? null,
            "questionnaire_id" => $data[1] ?? null,
            "means" => $data[2] ?? null,
            "created_at" => !empty($data[3] ?? '') ? \Carbon\Carbon::parse($data[3]) : now(),
            "updated_at" => !empty($data[4] ?? '') ? \Carbon\Carbon::parse($data[4]) : now(),
            "deleted_at" => null
        ]);
    }
        fclose($csvFile);

    }
}
