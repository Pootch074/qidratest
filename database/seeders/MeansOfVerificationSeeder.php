<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MeansOfVerificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $means = [
            [
                'questionnaire_id' => 18,
                'means' => 'Copy of Vision, Mission and Goals',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'questionnaire_id' => 18,
                'means' => 'IEC materials on the programs and services',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'questionnaire_id' => 19,
                'means' => 'Written policies and other related documents',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'questionnaire_id' => 19,
                'means' => 'Manual of Operations',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'questionnaire_id' => 20,
                'means' => 'Organizational Chart',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'questionnaire_id' => 20,
                'means' => 'Job description',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'questionnaire_id' => 20,
                'means' => 'Citizen\'s Charter',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'questionnaire_id' => 20,
                'means' => 'Individual Performance Contract',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'questionnaire_id' => 20,
                'means' => 'Office Order',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'questionnaire_id' => 21,
                'means' => 'PRC license',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'questionnaire_id' => 21,
                'means' => 'Appointment paper',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'questionnaire_id' => 21,
                'means' => 'Contract',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'questionnaire_id' => 21,
                'means' => 'Proof of PRC application renewal',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'questionnaire_id' => 22,
                'means' => 'Contract',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'questionnaire_id' => 22,
                'means' => 'Appointment paper',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'questionnaire_id' => 23,
                'means' => 'Contract',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'questionnaire_id' => 23,
                'means' => 'Appointment paper',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'questionnaire_id' => 23,
                'means' => 'Training Certificate/s',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ];

        DB::table('means_of_verifications')->insert($means);

    }
}
