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
        $user = [
            'first_name' => 'Super',
            'last_name' => 'Admin',
            'email' => 'admin@admin.com',
            'position' => 'Admin',
            'user_type' => 1,
            'status' => 1,
            'email_verified_at' => Carbon::now(),
            'password' => Hash::make('A7X9B2KQ'),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];

        DB::table('users')->insert($user);
    }
}
