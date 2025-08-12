<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {

        $this->call(UsersSeeder::class);

        $this->call(RegionsSeeder::class);
        $this->call(ProvincesSeeder::class);

        // Make sure questionnaires are seeded before weights
        $this->call(AssessmentSeeders::class);
        $this->call(ParentQuestionnaireSeeder::class);

        // Now we can seed weights safely
        $this->call(QuestionnaireWeightsSeeder::class);
    }
}
