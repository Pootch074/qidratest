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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('full_name')->nullable();
            $table->integer('queue_number');
            $table->enum('client_type', ['priority', 'regular']);
            
            $table->enum('ticket_status', ['issued', 'cancelled'])->nullable();

            $table->unsignedBigInteger('step_id')->nullable();
            $table->foreign('step_id')->references('id')->on('steps')->onDelete('set null');
            
            $table->unsignedBigInteger('window_id')->nullable();
            $table->foreign('window_id')->references('id')->on('windows')->onDelete('set null');
            
            $table->unsignedBigInteger('section_id')->nullable();
            $table->foreign('section_id')->references('id')->on('sections')->onDelete('set null');
            
            $table->enum('queue_status', ['waiting', 'pending', 'serving', 'completed'])->default('waiting');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
