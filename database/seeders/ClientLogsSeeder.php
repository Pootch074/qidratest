<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ClientLogs;

class ClientLogsSeeder extends Seeder
{
    public function run(): void
    {
        // Create 50 random client logs
        ClientLogs::factory()->count(50)->create();
    }
}
