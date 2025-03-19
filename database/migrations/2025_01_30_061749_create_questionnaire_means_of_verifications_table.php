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
        Schema::create('questionnaire_means_of_verifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('questionnaire_id')->constrained();
            $table->foreignId('means_of_verification_id')->constrained()->name('means_verification_fk');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questionnaire_means_of_verifications');
    }
};
