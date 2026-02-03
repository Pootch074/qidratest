<?php

namespace Database\Seeders;

use App\Models\ClientLogs;
use Illuminate\Database\Seeder;

class ClientLogsSeeder extends Seeder
{
    public function run(): void
    {
        // Create 50 random client logs
        ClientLogs::factory()->count(50)->create();
    }
}
