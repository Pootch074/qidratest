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
        Schema::create('scoring_sheets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assessment_id')->constrained();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('lgu_id')->constrained();
            $table->foreignId('assessment_year_id')->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scoring_sheets');
    }
};
