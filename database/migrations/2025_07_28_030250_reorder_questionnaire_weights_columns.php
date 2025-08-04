<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // Use raw SQL to reorder the column
        DB::statement('
            ALTER TABLE questionnaire_weights 
            MODIFY COLUMN questionnaire_id BIGINT UNSIGNED AFTER id
        ');
    }

    public function down(): void
    {
        // Optional: revert to original order (placing it after updated_at)
        DB::statement('
            ALTER TABLE questionnaire_weights 
            MODIFY COLUMN questionnaire_id BIGINT UNSIGNED AFTER updated_at
        ');
    }
};
