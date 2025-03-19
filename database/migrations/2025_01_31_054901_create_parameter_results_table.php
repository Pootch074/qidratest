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
        Schema::create('parameter_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assessment_id')->constrained();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('lgu_id')->constrained();
            $table->foreignId('assessment_year_id')->constrained();
            $table->date('assessment_date')->nullable();
            $table->string('final_rating_text')->nullable();
            $table->float('final_rating_value')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parameter_results');
    }
};
