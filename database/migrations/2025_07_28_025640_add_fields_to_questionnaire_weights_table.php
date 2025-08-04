<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('questionnaire_weights', function (Blueprint $table) {
            $table->foreignId('questionnaire_id')->constrained()->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('questionnaire_weights', function (Blueprint $table) {
            $table->dropColumn(['id', 'questionnaire_id']);
        });
    }
};

