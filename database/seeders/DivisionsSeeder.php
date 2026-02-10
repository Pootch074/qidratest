<?php

namespace Database\Seeders;

use App\Models\Office;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DivisionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('divisions')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // ✅ Dynamically fetch the office ID for “DSWD FO XI”
        $dswdfoxiId = Office::where('office_name', 'DSWD FO XI')->value('id');

        // Defensive check — prevents seeding if the office doesn’t exist
        if (! $dswdfoxiId) {
            $this->command->error('❌ Office "DSWD FO XI" not found. Please seed the offices table first.');

            return;
        }

        $now = Carbon::now();

        $divisions = [
            ['division_name' => 'Disaster Response Management Division', 'office_id' => $dswdfoxiId, 'created_at' => $now, 'updated_at' => $now],
            ['division_name' => 'General Administrative Support Services Division', 'office_id' => $dswdfoxiId, 'created_at' => $now, 'updated_at' => $now],
            ['division_name' => 'Financial Management Division', 'office_id' => $dswdfoxiId, 'created_at' => $now, 'updated_at' => $now],
            ['division_name' => 'Human Resource Management and Development Division', 'office_id' => $dswdfoxiId, 'created_at' => $now, 'updated_at' => $now],
            ['division_name' => 'Inovasions Division', 'office_id' => $dswdfoxiId, 'created_at' => $now, 'updated_at' => $now],
            ['division_name' => 'Office of the Regional Director', 'office_id' => $dswdfoxiId, 'created_at' => $now, 'updated_at' => $now],
            ['division_name' => 'Pantawid Pamilyang Pilipino Program Management Division', 'office_id' => $dswdfoxiId, 'created_at' => $now, 'updated_at' => $now],
            ['division_name' => 'Policy and Plans Division', 'office_id' => $dswdfoxiId, 'created_at' => $now, 'updated_at' => $now],
            ['division_name' => 'Promotive Services Division', 'office_id' => $dswdfoxiId, 'created_at' => $now, 'updated_at' => $now],
            ['division_name' => 'Protective Services Division', 'office_id' => $dswdfoxiId, 'created_at' => $now, 'updated_at' => $now],

            ['division_name' => 'Office of the Assistant Regional Director for Operation', 'office_id' => $dswdfoxiId, 'created_at' => $now, 'updated_at' => $now],
            ['division_name' => 'Office of the Assistant Regional Director for Administration', 'office_id' => $dswdfoxiId, 'created_at' => $now, 'updated_at' => $now],
            ['division_name' => 'Provincial Social Welfare and Development Office', 'office_id' => $dswdfoxiId, 'created_at' => $now, 'updated_at' => $now],
            ['division_name' => 'Resource Management', 'office_id' => $dswdfoxiId, 'created_at' => $now, 'updated_at' => $now],
            ['division_name' => 'Regional Juvenile Justice Welfare Council', 'office_id' => $dswdfoxiId, 'created_at' => $now, 'updated_at' => $now],
            ['division_name' => 'Innovations Division', 'office_id' => $dswdfoxiId, 'created_at' => $now, 'updated_at' => $now],

            ['division_name' => 'POO Davao Del Sur', 'office_id' => $dswdfoxiId, 'created_at' => $now, 'updated_at' => $now],
            ['division_name' => 'POO Davao Del Norte', 'office_id' => $dswdfoxiId, 'created_at' => $now, 'updated_at' => $now],
            ['division_name' => 'POO Davao Occidental', 'office_id' => $dswdfoxiId, 'created_at' => $now, 'updated_at' => $now],
            ['division_name' => 'POO Davao Oriental', 'office_id' => $dswdfoxiId, 'created_at' => $now, 'updated_at' => $now],
            ['division_name' => 'POO Davao De Oro', 'office_id' => $dswdfoxiId, 'created_at' => $now, 'updated_at' => $now],
        ];

        DB::table('divisions')->insert($divisions);

        $this->command->info('✅ Divisions seeded successfully for office: DSWD FO XI');
    }
}
