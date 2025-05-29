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
        Schema::create('period_assessments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('period_id')->constrained();
            $table->foreignId('lgu_id')->nullable()->constrained();
            $table->foreignId('rmt_id')->nullable()->constrained();
            $table->date('assessment_start_date')->nullable();
            $table->date('assessment_end_date')->nullable();
            $table->string('status')->default('pending'); // pending, on-going, completed, request_for_extension
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('period_assessments');
    }
};
