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
        Schema::create('assessments', function (Blueprint $table) {
            $table->id();
            $table->string('control_number');
            $table->date('date_of_interview');
            $table->time('time_began');
            $table->time('time_ended')->nullable();
            $table->foreignId('lgu_id')->constrained();
            $table->foreignId('assessment_year_id')->constrained();
            $table->foreignId('rmt_id')->constrained();
            $table->string('assessor')->nullable();
            $table->date('assessor_date_accomplished')->nullable();
            $table->string('team_leader')->nullable();
            $table->date('team_leader_date_accomplished')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assessments');
    }
};
