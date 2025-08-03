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
        // Deletes columns remarks and recommendations from assessment_questionnaires table
        Schema::table('assessment_questionnaires', function (Blueprint $table) {
            $table->dropColumn('remarks');
            $table->dropColumn('recommendations');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Put back remarks and recommendations text columns in assessment_questionnaires table
        Schema::table('assessment_questionnaires', function (Blueprint $table) {
            $table->text('remarks')->nullable();
            $table->text('recommendations')->nullable();
        });
    }
};
