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

        $users = [
            [
                'first_name' => 'CIS',
                'last_name' => 'Admin',
                'email' => env('ADMIN_EMAIL', 'cis@admin.com'),
                'position' => 'Admin',
                'section_id' => 1,
                'user_type' => 1,
                'assigned_category' => null,
                'window_id' => null,
                'status' => 1,
                'email_verified_at' => Carbon::now(),
                'password' => Hash::make('password'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'first_name' => 'CBSS',
                'last_name' => 'Admin',
                'email' => env('ADMIN_EMAIL', 'cbss@admin.com'),
                'position' => 'Admin',
                'section_id' => 2,
                'user_type' => 1,
                'assigned_category' => null,
                'window_id' => null,
                'status' => 1,
                'email_verified_at' => Carbon::now(),
                'password' => Hash::make('password'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'first_name' => 'PACD',
                'last_name' => 'User',
                'email' => env('ADMIN_EMAIL', 'pacd@gmail.com'),
                'position' => 'SWAID',
                'section_id' => null,
                'user_type' => 7,
                'assigned_category' => null,
                'window_id' => null,
                'status' => 1,
                'email_verified_at' => Carbon::now(),
                'password' => Hash::make('password'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'first_name' => 'Preassess',
                'last_name' => 'User',
                'email' => 'pu@gmail.com', // change as needed
                'position' => 'SWOII', // optional
                'section_id' => 1,
                'user_type' => 2,
                'assigned_category' => 'regular',
                'window_id' => 1,
                'status' => 1,
                'email_verified_at' => Carbon::now(),
                'password' => Hash::make('password'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'first_name' => 'Encode',
                'last_name' => 'User',
                'email' => 'eu@gmail.com', // change as needed
                'position' => 'SWOII', // optional
                'section_id' => 1,
                'user_type' => 3,
                'assigned_category' => 'regular',
                'window_id' => 2,
                'status' => 1,
                'email_verified_at' => Carbon::now(),
                'password' => Hash::make('password'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'first_name' => 'Assessment',
                'last_name' => 'User',
                'email' => 'au@gmail.com', // change as needed
                'position' => 'SWOII', // optional
                'section_id' => 1,
                'user_type' => 4,
                'assigned_category' => null,
                'window_id' => 3,
                'status' => 1,
                'email_verified_at' => Carbon::now(),
                'password' => Hash::make('password'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'first_name' => 'Release',
                'last_name' => 'User',
                'email' => 'ru@gmail.com', // change as needed
                'position' => 'SWOII', // optional
                'section_id' => 1,
                'user_type' => 5,
                'assigned_category' => null,
                'window_id' => 7,
                'status' => 1,
                'email_verified_at' => Carbon::now(),
                'password' => Hash::make('password'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'first_name' => 'CBSS',
                'last_name' => 'User',
                'email' => 'cbss@user.com', // change as needed
                'position' => 'SWOII', // optional
                'section_id' => 2,
                'user_type' => 6,
                'assigned_category' => null,
                'window_id' => 10,
                'status' => 1,
                'email_verified_at' => Carbon::now(),
                'password' => Hash::make('password'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        ];

        DB::table('users')->insert($users);
    }
}
