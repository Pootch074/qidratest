<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('users')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $user = [
            'first_name' => 'Super',
            'last_name' => 'Admin',
            'email' => env('ADMIN_EMAIL', 'admin@admin.com'),
            'position' => 'Admin',
            'user_type' => 1,
            'status' => 1,
            'email_verified_at' => Carbon::now(),
            'password' => Hash::make(env('ADMIN_PASSWORD', 'A7X9B2KQ')),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];

        DB::table('users')->insert($user);
    }
}
