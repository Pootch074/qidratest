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
        Schema::create('scoring_sheet_particulars', function (Blueprint $table) {
            $table->id();
            $table->foreignId('scoring_sheet_id')->constrained();
            $table->foreignid('parent_id')->constrained('scoring_sheet_particulars', 'id');;
            $table->string('indicator')->nullable();
            $table->float('previous_score')->nullable();
            $table->float('current_score')->nullable();
            $table->string('status')->nullable();
            $table->string('hindering_factors')->nullable();
            $table->text('facilitating_factors')->nullable();
            $table->text('areas_of_improvement')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scoring_sheet_particulars');
    }
};
