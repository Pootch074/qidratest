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
        Schema::table('questionnaires', function (Blueprint $table) {
            $table->integer('questionnaire_tree_id')->unsigned()->nullable()->after('reference_number');
            $table->dropForeign(['parent_id']); // Drop the foreign key constraint
            $table->unsignedBigInteger('parent_id')->default(0)->change(); // Change the column to default to 0
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('questionnaires', function (Blueprint $table) {
            $table->dropColumn('questionnaire_tree_id');
            $table->unsignedBigInteger('parent_id')->nullable()->default(null)->change();
            $table->foreign('parent_id')->references('id')->on('questionnaires');
        });
    }
};
