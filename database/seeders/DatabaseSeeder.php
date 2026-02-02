<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(OfficesSeeder::class);
        $this->call(DivisionsSeeder::class);
        $this->call(SectionsSeeder::class);
        $this->call(StepsSeeder::class);
        $this->call(WindowsSeeder::class);
        $this->call(UsersSeeder::class);
        $this->call(PositionsSeeder::class);
        $this->call(ClientLogsSeeder::class);
    }
}
