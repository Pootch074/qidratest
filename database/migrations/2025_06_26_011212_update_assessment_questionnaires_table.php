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
        //
        Schema::table('assessment_questionnaires', function(Blueprint $table) {
            $table->unsignedBigInteger('user_id')->after('number_of_beneficiaries');
            $table->unsignedBigInteger('lgu_id')->after('period_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('lgu_id')->references('id')->on('lgus')->onDelete('cascade');
            $table->text('recommendations')->after('remarks')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::table('assessment_questionnaires', function(Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['lgu_id']);
            $table->dropColumn('user_id');
            $table->dropColumn('recommendations');
            $table->dropColumn('lgu_id');
        });
    }
};
