<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('means_of_verifications', function (Blueprint $table) {
            $table->foreignId('questionnaire_id')->after('id');
            $table->softDeletes();
        });
        Schema::dropIfExists('questionnaire_means_of_verifications');

        Schema::table('questionnaire_levels', function (Blueprint $table) {
            $table->dropColumn('sdca_level_id');
            $table->integer('level')->after('questionnaire_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('means_of_verifications', function (Blueprint $table) {
            $table->dropColumn('questionnaire_id');
            $table->dropSoftDeletes();
        });

        Schema::create('questionnaire_means_of_verifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('questionnaire_id')->constrained();
            $table->foreignId('means_of_verification_id')->constrained()->name('means_verification_fk');
            $table->timestamps();
        });

        Schema::table('questionnaire_levels', function (Blueprint $table) {
            $table->foreignId('sdca_level_id');
            $table->dropColumn('level');
        });
    }
};
