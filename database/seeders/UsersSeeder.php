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
                'first_name' => 'Super',
                'last_name' => 'Admin',
                'email' => env('ADMIN_EMAIL', 'admin@admin.com'),
                'position' => 'Admin',
                'user_type' => 1,
                'assigned_category' => null,
                'window_id' => null,
                'status' => 1,
                'email_verified_at' => Carbon::now(),
                'password' => Hash::make(env('ADMIN_PASSWORD', 'A7X9B2KQ')),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'first_name' => 'Preassess',
                'last_name' => 'User',
                'email' => 'pu@gmail.com', // change as needed
                'position' => 'SWOII', // optional
                'user_type' => 5,
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
                'user_type' => 5,
                'assigned_category' => 'regular',
                'window_id' => 1,
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
                'user_type' => 5,
                'assigned_category' => null,
                'window_id' => 1,
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
                'user_type' => 5,
                'assigned_category' => null,
                'window_id' => 1,
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
