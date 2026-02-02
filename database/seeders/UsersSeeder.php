<?php

namespace Database\Seeders;

use App\Models\Division;
use App\Models\Office;
use App\Models\Section;
use App\Models\Step;
use App\Models\User;
use App\Models\Window;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        User::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $offices = [
            'dswdfoxi' => Office::where('office_name', 'DSWD FO XI')->first(),
        ];

        $divisions = [
            'psd' => Division::where('division_name', 'Protective Services Division')->first(),
        ];

        $sections = [
            'cbss' => Section::where('section_name', 'COMMUNITY-BASED SERVICES SECTION (CBSS)')->first(),
            'cis' => Section::where('section_name', 'CRISIS INTERVENTION SECTION')->first(),
        ];

        $now = Carbon::now();

        // Helper to get step ID
        $getStepId = fn ($section, $stepName) => Step::where('section_id', $section->id)
            ->where('step_name', $stepName)
            ->first()?->id;

        // Helper to get window ID
        $getWindowId = fn ($stepId, $windowNumber) => Window::where('step_id', $stepId)
            ->where('window_number', $windowNumber)
            ->first()?->id;

        // Define all users
        $users = [
            // Global accounts
            ['first_name' => 'Super', 'last_name' => 'Admin', 'user_type' => User::TYPE_SUPERADMIN, 'email' => 'superadmin@dswd.gov.ph'],
            // ['first_name' => 'IDScan', 'last_name' => 'User', 'user_type' => User::TYPE_IDSCAN, 'email' => 'idscan@dswd.gov.ph'],
            // ['first_name' => 'General', 'last_name' => 'PACD', 'position' => 'SWAID', 'user_type' => User::TYPE_PACD, 'email' => 'genpacd@dswd.gov.ph'],

            // CBSS accounts
            // ['division' => 'psd', 'first_name' => 'CBSS', 'last_name' => 'Admin', 'section' => 'cbss', 'user_type' => User::TYPE_ADMIN, 'email' => 'cbssadmin@dswd.gov.ph'],
            // ['division' => 'psd', 'first_name' => 'CBSS', 'last_name' => 'User', 'position' => 'Social Welfare Officer II', 'section' => 'cbss', 'user_type' => User::TYPE_USER, 'step' => 'None', 'window' => 1, 'email' => 'usercbss@dswd.gov.ph'],
            // ['division' => 'psd', 'first_name' => 'CBSS', 'last_name' => 'Display', 'email' => 'displaycbss@dswd.gov.ph', 'section' => 'cbss', 'user_type' => User::TYPE_DISPLAY],

            // CIS accounts
            // ['division' => 'psd', 'first_name' => 'CIS', 'last_name' => 'Admin', 'section' => 'cis', 'user_type' => User::TYPE_ADMIN, 'email' => 'cisadmin@dswd.gov.ph', 'status' => User::STATUS_ACTIVE],
            // ['division' => 'psd', 'first_name' => 'CIS', 'last_name' => 'PACD', 'position' => 'SWAID', 'section' => 'cis', 'user_type' => User::TYPE_PACD, 'email' => 'cispacd@dswd.gov.ph', 'status' => User::STATUS_ACTIVE],

            // CIS Pre-assessment
            // ['division' => 'psd', 'first_name' => 'Preasssess', 'last_name' => 'Regular', 'position' => 'Social Welfare Officer II', 'section' => 'cis', 'user_type' => User::TYPE_USER, 'assigned_category' => 'regular', 'step' => 'Pre-assessment', 'window' => 1, 'email' => 'preassessregu@dswd.gov.ph', 'status' => User::STATUS_ACTIVE],
            // ['division' => 'psd', 'first_name' => 'Preasssess', 'last_name' => 'Priority', 'position' => 'Social Welfare Officer II', 'section' => 'cis', 'user_type' => User::TYPE_USER, 'assigned_category' => 'priority', 'step' => 'Pre-assessment', 'window' => 1, 'email' => 'preassessprio@dswd.gov.ph', 'status' => User::STATUS_ACTIVE],

            // CIS Encoder
            // ['division' => 'psd', 'first_name' => 'Encoding', 'last_name' => 'Regular', 'position' => 'Social Welfare Officer II', 'section' => 'cis', 'user_type' => User::TYPE_USER, 'assigned_category' => 'regular', 'step' => 'Encode', 'window' => 1, 'email' => 'encodingregu1@dswd.gov.ph', 'status' => User::STATUS_ACTIVE],
            // ['division' => 'psd', 'first_name' => 'Encoding', 'last_name' => 'Regular', 'position' => 'Social Welfare Officer II', 'section' => 'cis', 'user_type' => User::TYPE_USER, 'assigned_category' => 'regular', 'step' => 'Encode', 'window' => 2, 'email' => 'encodingregu2@dswd.gov.ph', 'status' => User::STATUS_ACTIVE],
            // ['division' => 'psd', 'first_name' => 'Encoding', 'last_name' => 'Priority', 'position' => 'Social Welfare Officer II', 'section' => 'cis', 'user_type' => User::TYPE_USER, 'assigned_category' => 'priority', 'step' => 'Encode', 'window' => 1, 'email' => 'encodingprio1@dswd.gov.ph', 'status' => User::STATUS_ACTIVE],

            // CIS Assessment
            // ['division' => 'psd', 'first_name' => 'Assessment', 'last_name' => 'Window1', 'position' => 'Social Welfare Officer II', 'section' => 'cis', 'user_type' => User::TYPE_USER, 'assigned_category' => 'both', 'step' => 'Assessment', 'window' => 1, 'email' => 'assessment1@dswd.gov.ph', 'status' => User::STATUS_ACTIVE],
            // ['division' => 'psd', 'first_name' => 'Assessment', 'last_name' => 'Window2', 'position' => 'Social Welfare Officer II', 'section' => 'cis', 'user_type' => User::TYPE_USER, 'assigned_category' => 'both', 'step' => 'Assessment', 'window' => 2, 'email' => 'assessment2@dswd.gov.ph', 'status' => User::STATUS_ACTIVE],
            // ['division' => 'psd', 'first_name' => 'Assessment', 'last_name' => 'Window3', 'position' => 'Social Welfare Officer II', 'section' => 'cis', 'user_type' => User::TYPE_USER, 'assigned_category' => 'both', 'step' => 'Assessment', 'window' => 3, 'email' => 'assessment3@dswd.gov.ph', 'status' => User::STATUS_ACTIVE],
            // ['division' => 'psd', 'first_name' => 'Assessment', 'last_name' => 'Window4', 'position' => 'Social Welfare Officer II', 'section' => 'cis', 'user_type' => User::TYPE_USER, 'assigned_category' => 'both', 'step' => 'Assessment', 'window' => 4, 'email' => 'assessment4@dswd.gov.ph', 'status' => User::STATUS_ACTIVE],

            // // CIS Releasing
            // ['division' => 'psd', 'first_name' => 'Releasing', 'last_name' => 'Window1', 'position' => 'Social Welfare Officer II', 'section' => 'cis', 'user_type' => User::TYPE_USER, 'assigned_category' => 'both', 'step' => 'Release', 'window' => 1, 'email' => 'releasing1@dswd.gov.ph', 'status' => User::STATUS_ACTIVE],
            // ['division' => 'psd', 'first_name' => 'Releasing', 'last_name' => 'Window2', 'position' => 'Social Welfare Officer II', 'section' => 'cis', 'user_type' => User::TYPE_USER, 'assigned_category' => 'both', 'step' => 'Release', 'window' => 2, 'email' => 'releasing2@dswd.gov.ph', 'status' => User::STATUS_ACTIVE],
            // ['division' => 'psd', 'first_name' => 'Releasing', 'last_name' => 'Window3', 'position' => 'Social Welfare Officer II', 'section' => 'cis', 'user_type' => User::TYPE_USER, 'assigned_category' => 'both', 'step' => 'Release', 'window' => 3, 'email' => 'releasing3@dswd.gov.ph', 'status' => User::STATUS_ACTIVE],

            // CIS Display
            // ['division' => 'psd', 'first_name' => 'Display', 'last_name' => 'Regular', 'email' => 'cisdisplayregu@dswd.gov.ph', 'section' => 'cis', 'user_type' => User::TYPE_DISPLAY, 'assigned_category' => 'regular', 'status' => User::STATUS_ACTIVE],
            // ['division' => 'psd', 'first_name' => 'Display', 'last_name' => 'Priority', 'email' => 'cisdisplayprio@dswd.gov.ph', 'section' => 'cis', 'user_type' => User::TYPE_DISPLAY, 'assigned_category' => 'priority', 'status' => User::STATUS_ACTIVE],

            ['division' => 'psd', 'section' => 'cis', 'first_name' => 'Bladymer', 'last_name' => 'Ybalio', 'email' => 'baybalio@dswd.gov.ph', 'position' => 'Computer Programmer III', 'user_type' => User::TYPE_ADMIN, 'status' => User::STATUS_ACTIVE, 'email_is_verified' => true],
            ['division' => 'psd', 'section' => 'cis', 'first_name' => 'Blad', 'last_name' => 'Sparrow', 'email' => 'bladsparrow74@gmail.com', 'position' => 'Project Development Officer II', 'user_type' => User::TYPE_PACD,
            //  'assigned_category' => 'regular', 'step' => 'Release', 'window' => 3,
               'status' => User::STATUS_ACTIVE, 'email_is_verified' => true],
            ['division' => 'psd', 'section' => 'cis', 'first_name' => 'Yhena', 'last_name' => 'Villamil', 'email' => 'yvvillamil@dswd.gov.ph', 'position' => 'Computer Programmer III',  'user_type' => User::TYPE_ADMIN, 'status' => User::STATUS_ACTIVE, 'email_is_verified' => true],

            ['division' => 'psd', 'section' => 'cis', 'first_name' => 'Yhena', 'last_name' => 'Villamil', 'email' => 'villamil.yhena@gmail.com', 'position' => 'Administrative Assistant I',  'user_type' => User::TYPE_USER, 'assigned_category' => 'both', 'step' => 'Assessment', 'window' => 3, 'status' => User::STATUS_ACTIVE, 'email_is_verified' => true],
            ['division' => 'psd', 'section' => 'cis', 'first_name' => 'Francis', 'last_name' => 'Sale', 'email' => 'fosale@dswd.gov.ph', 'position' => 'Administrative Aide II', 'user_type' => User::TYPE_USER, 'assigned_category' => 'regular', 'step' => 'Pre-assessment', 'window' => 1, 'status' => User::STATUS_INACTIVE, 'email_is_verified' => true],
            ['division' => 'psd', 'section' => 'cis', 'first_name' => 'Kim', 'last_name' => 'Juanico', 'email' => 'krajuanico@dswd.gov.ph', 'position' => 'Administrative Aide I', 'user_type' => User::TYPE_USER, 'assigned_category' => 'priority', 'step' => 'Pre-assessment', 'window' => 1, 'status' => User::STATUS_INACTIVE, 'email_is_verified' => true],
            ['division' => 'psd', 'section' => 'cis', 'first_name' => 'Jay', 'last_name' => 'Villas', 'email' => 'fjlvillas@dswd.gov.ph', 'position' => 'Administrative Aide IV', 'user_type' => User::TYPE_USER, 'assigned_category' => 'regular', 'step' => 'Encode', 'window' => 1, 'status' => User::STATUS_INACTIVE, 'email_is_verified' => true],

            ['division' => 'psd', 'section' => 'cis', 'first_name' => 'Dan', 'last_name' => 'Umbay', 'email' => 'jdcumbay@dswd.gov.ph', 'position' => 'Administrative Aide I', 'user_type' => User::TYPE_USER, 'assigned_category' => 'priority', 'step' => 'Encode', 'window' => 1, 'status' => User::STATUS_INACTIVE, 'email_is_verified' => true],
            ['division' => 'psd', 'section' => 'cis', 'first_name' => 'Chard', 'last_name' => 'Tams', 'email' => 'rgtamala@dswd.gov.ph', 'position' => 'Administrative Aide I', 'user_type' => User::TYPE_USER, 'assigned_category' => 'both', 'step' => 'Release', 'window' => 1, 'status' => User::STATUS_INACTIVE, 'email_is_verified' => true],



            // ['division' => 'psd', 'section' => 'cis', 'first_name' => 'Juan', 'last_name' => 'Dela Cruz', 'email' => 'jdcruz@dswd.gov.ph', 'position' => 'Social Welfare Officer II', 'user_type' => User::TYPE_USER, 'assigned_category' => 'regular', 'status' => User::STATUS_INACTIVE,  'email_is_verified' => true],
        ];

        // Loop and create
        foreach ($users as $userData) {
            if (isset($userData['division'])) {
                $userData['division_id'] = $divisions[$userData['division']]->id;
                unset($userData['division']);
            }

            if (isset($userData['section'])) {
                $userData['section_id'] = $sections[$userData['section']]->id;
                unset($userData['section']);
            }

            if (isset($userData['step']) && isset($userData['section_id'])) {
                $userData['step_id'] = $getStepId(
                    Section::find($userData['section_id']),
                    $userData['step']
                );
                unset($userData['step']);
            }

            if (isset($userData['window']) && isset($userData['step_id'])) {
                $userData['window_id'] = $getWindowId($userData['step_id'], $userData['window']);
                unset($userData['window']);
            }

            User::create(array_merge($userData, [
                'created_at' => $now,
                'updated_at' => $now,
            ]));
        }
    }
}
