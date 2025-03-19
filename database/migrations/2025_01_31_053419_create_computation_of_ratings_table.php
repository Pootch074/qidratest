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
        Schema::create('computation_of_ratings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')->constrained('computation_of_ratings', 'id');;
            $table->foreignId('assessment_id')->constrained();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('lgu_id')->constrained();
            $table->foreignId('assessment_year_id')->constrained();
            $table->float('total_desired_score')->nullable();
            $table->float('total_previous_index_score')->nullable();
            $table->float('total_raw_score')->nullable();
            $table->float('total_new_index_score')->nullable();
            $table->float('total_movement_of_index_score')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('computation_of_ratings');
    }
};
