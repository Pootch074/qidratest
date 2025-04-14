<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class QuestionnaireLevelsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $levels = [
            // Questionnaire ID 18
            ['questionnaire_id' => 18, 'level' => 0, 'remarks' => 'Did not meet the minimum requirement', 'created_at' => now(), 'updated_at' => now()],
            ['questionnaire_id' => 18, 'level' => 1, 'remarks' => 'With available vision, mission and goals', 'created_at' => now(), 'updated_at' => now()],
            ['questionnaire_id' => 18, 'level' => 2, 'remarks' => 'A written vision, mission and goals posted in visible and conspicuous areas within the office', 'created_at' => now(), 'updated_at' => now()],
            ['questionnaire_id' => 18, 'level' => 3, 'remarks' => 'Vision, mission, and goals should be readable and posted in visible and conspicuous areas within the office. There is existing IEC materials about the office\'s programs and services anchored on the LSWDO\'s VMG', 'created_at' => now(), 'updated_at' => now()],

            // Questionnaire ID 19
            ['questionnaire_id' => 19, 'level' => 0, 'remarks' => 'Did not meet the minimum requirement', 'created_at' => now(), 'updated_at' => now()],
            ['questionnaire_id' => 19, 'level' => 1, 'remarks' => 'With compiled documents reflecting the program processes and information', 'created_at' => now(), 'updated_at' => now()],
            ['questionnaire_id' => 19, 'level' => 2, 'remarks' => 'Information about the policies/guidelines on the implementation of LSWDO’s programs and services, through manuals, citizen’s charter and the likes are available and accessible for use of staff and their clients but are not yet in the form of manual', 'created_at' => now(), 'updated_at' => now()],
            ['questionnaire_id' => 19, 'level' => 3, 'remarks' => 'A Manual of Operations is developed and updated (at least within 3 years) with the consolidated policies/guidelines for implementation of various services/programs of the LSWDO', 'created_at' => now(), 'updated_at' => now()],

            // Questionnaire ID 20
            ['questionnaire_id' => 20, 'level' => 0, 'remarks' => 'Did not meet the minimum requirement', 'created_at' => now(), 'updated_at' => now()],
            ['questionnaire_id' => 20, 'level' => 1, 'remarks' => 'With existing organizational structure (but not posted)', 'created_at' => now(), 'updated_at' => now()],
            ['questionnaire_id' => 20, 'level' => 2, 'remarks' => 'An existing organizational structure, clearly defining the delineation of responsibilities and lines of authority amongst staff is posted in visible areas', 'created_at' => now(), 'updated_at' => now()],
            ['questionnaire_id' => 20, 'level' => 3, 'remarks' => 'LWDO\'s staff perform functions in accordance with the prescribed duties and responsibilities as indicated in a Special Order or  Competency- Based Job Description or any similar documents', 'created_at' => now(), 'updated_at' => now()],

            // Questionnaire ID 21
            ['questionnaire_id' => 21, 'level' => 0, 'remarks' => 'Did not meet the minimum requirement', 'created_at' => now(), 'updated_at' => now()],
            ['questionnaire_id' => 21, 'level' => 1, 'remarks' => 'Head of LSWDO is a registered social worker (not necessarily holding a plantilla position)', 'created_at' => now(), 'updated_at' => now()],
            ['questionnaire_id' => 21, 'level' => 2, 'remarks' => 'Head of LSWDO is a registered social worker and holding a plantilla position for the implementation of programs and services <p><b>PSWDO:</b> SG 25 and below</p><p><b>CSWDO:</b> HUCs: at least SG 25; Component Cities: at least SG 24</p><p><b>MSWDO:</b> 1st to 3rd class municipalities - at least SG 23; 4th class to 6th class municipalities - at least SG 18</p>', 'created_at' => now(), 'updated_at' => now()],
            ['questionnaire_id' => 21, 'level' => 3, 'remarks' => 'Head of LSWDO is a registered social worker holding a plantilla position for the implementation of programs and services <p><b>PSWDO:</b> At least SG 26</p><p><b>CSWDO:</b> HUCs: at least SG 26; Component Cities: at least SG 25)</p><p><b>MSWDO:</b> 1st to 3rd class municipalities - at least SG 24; 4th class to 6th class municipalities - at least SG 23</p>', 'created_at' => now(), 'updated_at' => now()],

            // Questionnaire ID 22
            ['questionnaire_id' => 22, 'level' => 0, 'remarks' => 'Did not meet the minimum requirement', 'created_at' => now(), 'updated_at' => now()],
            ['questionnaire_id' => 22, 'level' => 1, 'remarks' => 'One (1) technical staff is hired (casual/job order) for the implementation of programs and services', 'created_at' => now(), 'updated_at' => now()],
            ['questionnaire_id' => 22, 'level' => 2, 'remarks' => 'One (1) technical staff is holding a plantilla position for the implementation of programs and services <p><b>PSWDO:</b> At least SG 18 for 1st to 3rd class provinces; at least SG 15 for 4th to 6th class provinces</p><p><b>CSWDO:</b> At least SG 18 for HUCs, Component City, 1st & 2nd class cities; at least SG 15 for 3rd to 6th class cities)</p><p><b>MSWDO:</b> At least SG 18 for 1st to 3rd class municipalities and at least SG 11 for 4th to 6th class municipalities</p>', 'created_at' => now(), 'updated_at' => now()],
            ['questionnaire_id' => 22, 'level' => 3, 'remarks' => 'At least two (2) technical staff are holding a plantilla position for the implementation of programs and services <p><b>PSWDO:</b> At least SG 18 for 1st to 3rd class provinces; at least SG 15 for 4th to 6th class provinces</p><p><b>CSWDO:</b> At least SG 18 for HUCs, Component City, 1st & 2nd class cities; at least SG 15 for 3rd to 6th class cities</p><p><b>MSWDO:</b> At least SG 18 for 1st to 3rd class municipalities and at least SG 11 for 4th to 6th class municipalities</p>', 'created_at' => now(), 'updated_at' => now()],

            // Questionnaire ID 23
            ['questionnaire_id' => 23, 'level' => 0, 'remarks' => 'Did not meet the minimum requirement', 'created_at' => now(), 'updated_at' => now()],
            ['questionnaire_id' => 23, 'level' => 1, 'remarks' => 'One (1) Registered Social Worker is hired, assigned, and trained as case manager', 'created_at' => now(), 'updated_at' => now()],
            ['questionnaire_id' => 23, 'level' => 2, 'remarks' => 'One (1) Registered Social Worker holding at least <b>SWO I</b> is hired with plantilla position, assigned as case manager', 'created_at' => now(), 'updated_at' => now()],
            ['questionnaire_id' => 23, 'level' => 3, 'remarks' => 'A Registered Social Worker holding at least <b>SWO II</b> with plantilla position is hired and assigned as case manager', 'created_at' => now(), 'updated_at' => now()],

            // Questionnaire ID 24
            ['questionnaire_id' => 24, 'level' => 0, 'remarks' => 'Did not meet the minimum requirement', 'created_at' => now(), 'updated_at' => now()],
            ['questionnaire_id' => 24, 'level' => 1, 'remarks' => 'One (1) administrative staff is hired to assist in the operations of the office', 'created_at' => now(), 'updated_at' => now()],
            ['questionnaire_id' => 24, 'level' => 2, 'remarks' => 'One (1) administrative staff is hired and holding a plantilla position', 'created_at' => now(), 'updated_at' => now()],
            ['questionnaire_id' => 24, 'level' => 3, 'remarks' => 'More than 1 administrative staff are hired holding a plantilla position to assist in the operations of the office', 'created_at' => now(), 'updated_at' => now()],

            // Questionnaire ID 25
            ['questionnaire_id' => 25, 'level' => 0, 'remarks' => 'Did not meet the minimum requirement', 'created_at' => now(), 'updated_at' => now()],
            ['questionnaire_id' => 25, 'level' => 1, 'remarks' => 'Pre-Marriage Orientation and Counseling Team is organized as per EO issued by LCE and C/MSWDO as mandatory member with his/her alternate member are trained', 'created_at' => now(), 'updated_at' => now()],
            ['questionnaire_id' => 25, 'level' => 2, 'remarks' => 'C/MSWDO as mandatory member of the Pre-Marriage Orientation and Counseling Team is accredited PM counselor', 'created_at' => now(), 'updated_at' => now()],
            ['questionnaire_id' => 25, 'level' => 3, 'remarks' => 'C/MSWDO as mandatory member and alternate member of the Pre-Marriage Orientation and Counseling Team is accredited and all members are trained', 'created_at' => now(), 'updated_at' => now()],

            // Questionnaire ID 26
            ['questionnaire_id' => 26, 'level' => 0, 'remarks' => 'Did not meet the minimum requirement', 'created_at' => now(), 'updated_at' => now()],
            ['questionnaire_id' => 26, 'level' => 1, 'remarks' => 'One (1) Social Worker Managing Court Cases is trained', 'created_at' => now(), 'updated_at' => now()],
            ['questionnaire_id' => 26, 'level' => 2, 'remarks' => 'One (1) Social Worker Managing Court Cases is accredited.', 'created_at' => now(), 'updated_at' => now()],
            ['questionnaire_id' => 26, 'level' => 3, 'remarks' => 'More than one (1) Social Worker Managing Court Cases are trained and accredited', 'created_at' => now(), 'updated_at' => now()],

            // Questionnaire ID 27
            ['questionnaire_id' => 27, 'level' => 0, 'remarks' => 'Did not meet the minimum requirement', 'created_at' => now(), 'updated_at' => now()],
            ['questionnaire_id' => 27, 'level' => 1, 'remarks' => 'Less than 50% of personnel have attended at least 1 relevant training/seminar in the past 3 years', 'created_at' => now(), 'updated_at' => now()],
            ['questionnaire_id' => 27, 'level' => 2, 'remarks' => '50%-80% of personnel have attended at least 1 relevant training/seminar in the past 3 years', 'created_at' => now(), 'updated_at' => now()],
            ['questionnaire_id' => 27, 'level' => 3, 'remarks' => '81-100% of personnel have attended at least 1 relevant training/seminar in the past 3 years', 'created_at' => now(), 'updated_at' => now()],

            // Questionnaire ID 28
            ['questionnaire_id' => 28, 'level' => 0, 'remarks' => 'Did not meet the minimum requirement', 'created_at' => now(), 'updated_at' => now()],
            ['questionnaire_id' => 28, 'level' => 1, 'remarks' => 'There is an existing performance appraisal system being utilized by the LSWDO', 'created_at' => now(), 'updated_at' => now()],
            ['questionnaire_id' => 28, 'level' => 2, 'remarks' => 'Performance Appraisal system prescribed by the Civil Service is being utilized by the LSWDO', 'created_at' => now(), 'updated_at' => now()],
            ['questionnaire_id' => 28, 'level' => 3, 'remarks' => 'Performance Appraisal system prescribed by the Civil Service is being utilized by the LSWDO as basis for promotion and development', 'created_at' => now(), 'updated_at' => now()],

            // Questionnaire ID 29
            ['questionnaire_id' => 29, 'level' => 0, 'remarks' => 'Did not meet the minimum requirement', 'created_at' => now(), 'updated_at' => now()],
            ['questionnaire_id' => 29, 'level' => 1, 'remarks' => 'There is a system for compensation but not in accordance with existing wages, benefits and incentives rules and regulations such as the Salary Standardization Law', 'created_at' => now(), 'updated_at' => now()],
            ['questionnaire_id' => 29, 'level' => 2, 'remarks' => 'Compensation of LSWDOs and staff comply with existing wages, benefits and incentives rules and regulations such as the Salary Standardization Law', 'created_at' => now(), 'updated_at' => now()],
            ['questionnaire_id' => 29, 'level' => 3, 'remarks' => 'Rewards and incentive schemes are in place given by the LGU', 'created_at' => now(), 'updated_at' => now()],
        ];

        DB::table('questionnaire_levels')->insert($levels);
    }
}
