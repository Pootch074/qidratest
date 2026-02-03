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
        Schema::create('user_assignments_log', function (Blueprint $table) {
            $table->id();

            // IDs
            $table->unsignedBigInteger('section_id');
            $table->unsignedBigInteger('admin_id');
            $table->unsignedBigInteger('target_user_id');
            $table->unsignedBigInteger('assignment_id');

            // Role changes
            $table->string('role_before');
            $table->string('role_after');

            // Step changes (nullable)
            $table->string('step_before')->nullable();
            $table->string('step_after')->nullable();

            // Window changes (nullable)
            $table->string('window_before')->nullable();
            $table->string('window_after')->nullable();

            // Optional: you can add assigned_category logging if needed
            $table->string('assigned_category_before')->nullable();
            $table->string('assigned_category_after')->nullable();

            $table->timestamps();

            // Indexes for faster queries
            $table->index('section_id');
            $table->index('admin_id');
            $table->index('target_user_id');
            $table->index('assignment_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_assignments_log');
    }
};
