<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('
            ALTER TABLE questionnaire_weights
            MODIFY COLUMN weight DECIMAL(5,3)
        ');
    }

    public function down(): void
    {
        DB::statement('
            ALTER TABLE questionnaire_weights
            MODIFY COLUMN weight DECIMAL(5,2)
        ');
    }
};

