<?php

namespace Database\Seeders;

use App\Models\Section;
use App\Models\Office;
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
            ['office' => 'dswdfoxi', 'first_name' => 'Super', 'last_name' => 'Admin', 'user_type' => User::TYPE_SUPERADMIN, 'email' => 'superadmin@dswd.gov.ph'],
            ['office' => 'dswdfoxi', 'first_name' => 'IDScan', 'last_name' => 'User', 'user_type' => User::TYPE_IDSCAN, 'email' => 'idscan@dswd.gov.ph'],
            ['office' => 'dswdfoxi', 'first_name' => 'General', 'last_name' => 'PACD', 'position' => 'SWAID', 'user_type' => User::TYPE_PACD, 'email' => 'genpacd@dswd.gov.ph'],

            // CBSS accounts
            ['office' => 'dswdfoxi', 'first_name' => 'CBSS', 'last_name' => 'Admin', 'section' => 'cbss', 'user_type' => User::TYPE_ADMIN, 'email' => 'cbssadmin@dswd.gov.ph'],
            ['office' => 'dswdfoxi', 'first_name' => 'CBSS', 'last_name' => 'User', 'position' => 'SWOII', 'section' => 'cbss', 'user_type' => User::TYPE_USER, 'step' => 'None', 'window' => 1, 'email' => 'usercbss@dswd.gov.ph'],
            ['office' => 'dswdfoxi', 'first_name' => 'CBSS', 'last_name' => 'Display', 'email' => 'displaycbss@dswd.gov.ph', 'section' => 'cbss', 'user_type' => User::TYPE_DISPLAY],

            // CIS accounts
            ['office' => 'dswdfoxi', 'first_name' => 'CIS', 'last_name' => 'Admin', 'section' => 'cis', 'user_type' => User::TYPE_ADMIN, 'email' => 'cisadmin@dswd.gov.ph', 'status' => User::STATUS_ACTIVE],
            ['office' => 'dswdfoxi', 'first_name' => 'CIS', 'last_name' => 'PACD', 'position' => 'SWAID', 'section' => 'cis', 'user_type' => User::TYPE_PACD, 'email' => 'cispacd@dswd.gov.ph', 'status' => User::STATUS_ACTIVE],

            // CIS Pre-assessment
            ['office' => 'dswdfoxi', 'first_name' => 'Preasssess', 'last_name' => 'Regular', 'position' => 'SWOII', 'section' => 'cis', 'user_type' => User::TYPE_USER, 'assigned_category' => 'regular', 'step' => 'Pre-assessment', 'window' => 1, 'email' => 'preassessregu@dswd.gov.ph', 'status' => User::STATUS_ACTIVE],
            ['office' => 'dswdfoxi', 'first_name' => 'Preasssess', 'last_name' => 'Priority', 'position' => 'SWOII', 'section' => 'cis', 'user_type' => User::TYPE_USER, 'assigned_category' => 'priority', 'step' => 'Pre-assessment', 'window' => 1, 'email' => 'preassessprio@dswd.gov.ph', 'status' => User::STATUS_ACTIVE],

            // CIS Encoder
            ['office' => 'dswdfoxi', 'first_name' => 'Encoding', 'last_name' => 'Regular', 'position' => 'SWOII', 'section' => 'cis', 'user_type' => User::TYPE_USER, 'assigned_category' => 'regular', 'step' => 'Encode', 'window' => 1, 'email' => 'encodingregu1@dswd.gov.ph', 'status' => User::STATUS_ACTIVE],
            ['office' => 'dswdfoxi', 'first_name' => 'Encoding', 'last_name' => 'Regular', 'position' => 'SWOII', 'section' => 'cis', 'user_type' => User::TYPE_USER, 'assigned_category' => 'regular', 'step' => 'Encode', 'window' => 2, 'email' => 'encodingregu2@dswd.gov.ph', 'status' => User::STATUS_ACTIVE],
            ['office' => 'dswdfoxi', 'first_name' => 'Encoding', 'last_name' => 'Priority', 'position' => 'SWOII', 'section' => 'cis', 'user_type' => User::TYPE_USER, 'assigned_category' => 'priority', 'step' => 'Encode', 'window' => 1, 'email' => 'encodingprio1@dswd.gov.ph', 'status' => User::STATUS_ACTIVE],

            // CIS Assessment
            ['office' => 'dswdfoxi', 'first_name' => 'Assessment', 'last_name' => 'Window1', 'position' => 'SWOII', 'section' => 'cis', 'user_type' => User::TYPE_USER, 'assigned_category' => 'both', 'step' => 'Assessment', 'window' => 1, 'email' => 'assessment1@dswd.gov.ph', 'status' => User::STATUS_ACTIVE],
            ['office' => 'dswdfoxi', 'first_name' => 'Assessment', 'last_name' => 'Window2', 'position' => 'SWOII', 'section' => 'cis', 'user_type' => User::TYPE_USER, 'assigned_category' => 'both', 'step' => 'Assessment', 'window' => 2, 'email' => 'assessment2@dswd.gov.ph', 'status' => User::STATUS_ACTIVE],
            ['office' => 'dswdfoxi', 'first_name' => 'Assessment', 'last_name' => 'Window3', 'position' => 'SWOII', 'section' => 'cis', 'user_type' => User::TYPE_USER, 'assigned_category' => 'both', 'step' => 'Assessment', 'window' => 3, 'email' => 'assessment3@dswd.gov.ph', 'status' => User::STATUS_ACTIVE],
            ['office' => 'dswdfoxi', 'first_name' => 'Assessment', 'last_name' => 'Window4', 'position' => 'SWOII', 'section' => 'cis', 'user_type' => User::TYPE_USER, 'assigned_category' => 'both', 'step' => 'Assessment', 'window' => 4, 'email' => 'assessment4@dswd.gov.ph', 'status' => User::STATUS_ACTIVE],

            // CIS Releasing
            ['office' => 'dswdfoxi', 'first_name' => 'Releasing', 'last_name' => 'Window1', 'position' => 'SWOII', 'section' => 'cis', 'user_type' => User::TYPE_USER, 'assigned_category' => 'both', 'step' => 'Release', 'window' => 1, 'email' => 'releasing1@dswd.gov.ph', 'status' => User::STATUS_ACTIVE],
            ['office' => 'dswdfoxi', 'first_name' => 'Releasing', 'last_name' => 'Window2', 'position' => 'SWOII', 'section' => 'cis', 'user_type' => User::TYPE_USER, 'assigned_category' => 'both', 'step' => 'Release', 'window' => 2, 'email' => 'releasing2@dswd.gov.ph', 'status' => User::STATUS_ACTIVE],
            ['office' => 'dswdfoxi', 'first_name' => 'Releasing', 'last_name' => 'Window3', 'position' => 'SWOII', 'section' => 'cis', 'user_type' => User::TYPE_USER, 'assigned_category' => 'both', 'step' => 'Release', 'window' => 3, 'email' => 'releasing3@dswd.gov.ph', 'status' => User::STATUS_ACTIVE],

            // CIS Display
            ['office' => 'dswdfoxi', 'first_name' => 'Display', 'last_name' => 'Regular', 'email' => 'cisdisplayregu@dswd.gov.ph', 'section' => 'cis', 'user_type' => User::TYPE_DISPLAY, 'assigned_category' => 'regular', 'status' => User::STATUS_ACTIVE],
            ['office' => 'dswdfoxi', 'first_name' => 'Display', 'last_name' => 'Priority', 'email' => 'cisdisplayprio@dswd.gov.ph', 'section' => 'cis', 'user_type' => User::TYPE_DISPLAY, 'assigned_category' => 'priority', 'status' => User::STATUS_ACTIVE],
        ];

        // Loop and create
        foreach ($users as $userData) {
            if (isset($userData['office'])) {
                $userData['office_id'] = $offices[$userData['office']]->id;
                unset($userData['office']);
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
