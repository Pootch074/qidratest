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
        Schema::create('assessment_remarks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('period_id')->constrained();
            $table->foreignId('lgu_id')->constrained();
            $table->foreignId('questionnaire_id')->constrained();
            $table->text('remarks');
            $table->foreignId('user_id')->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assessment_remarks');
    }
};
