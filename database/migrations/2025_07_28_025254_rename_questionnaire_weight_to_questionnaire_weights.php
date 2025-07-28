<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::rename('questionnaire_weight', 'questionnaire_weights');
    }

    public function down(): void
    {
        Schema::rename('questionnaire_weights', 'questionnaire_weight');
    }
};
