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
        Schema::table('assessment_questionnaires', function (Blueprint $table) {
            // Drop the old foreign key constraint
            $table->dropForeign(['assessment_id']);
        });

        Schema::table('assessment_questionnaires', function (Blueprint $table) {
            // Rename the column
            $table->renameColumn('assessment_id', 'period_id');
        });

        Schema::table('assessment_questionnaires', function (Blueprint $table) {
            // Recreate the foreign key constraint
            $table->foreign('period_id')->references('id')->on('periods')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('assessment_questionnaires', function (Blueprint $table) {
            // Drop the foreign key constraint
            $table->dropForeign(['period_id']);
        });

        Schema::table('assessment_questionnaires', function (Blueprint $table) {
            // Rename column back
            $table->renameColumn('period_id', 'assessment_id');
        });

        Schema::table('assessment_questionnaires', function (Blueprint $table) {
            // Restore original foreign key constraint
            $table->foreign('assessment_id')->references('id')->on('assessments')->onDelete('cascade');
        });
    }
};
