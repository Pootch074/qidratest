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
        Schema::create('computation_of_rating_particulars', function (Blueprint $table) {
            $table->id();
            $table->foreignId('computation_of_rating_id')->constrained()->name('computation_rating_fk');;
            $table->foreignId('parent_id')->constrained('computation_of_ratings', 'id');
            $table->string('parameter')->nullable();
            $table->float('weight_per_indicator')->nullable();
            $table->float('desired_score')->nullable();
            $table->float('previous_index_score')->nullable();
            $table->float('raw_score')->nullable();
            $table->float('new_index_score')->nullable();
            $table->string('status')->nullable();
            $table->float('movement_of_index_score')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('computation_of_rating_particulars');
    }
};
