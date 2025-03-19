<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('lgu_questionnaires', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lgu_id')->constrained();
            $table->foreignId('questionnaire_id')->constrained();
            $table->enum('status', ['applicable', 'not applicable'])->default('applicable');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lgu_questionnaires');
    }
};
