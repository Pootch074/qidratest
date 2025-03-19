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
        Schema::create('lgus', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('city');
            $table->string('province');
            $table->foreignId('region_id')->constrained();
            $table->string('office_address')->nullable();
            $table->string('telephone')->nullable();
            $table->string('mobile_number')->nullable();
            $table->string('email_address')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lgus');
    }
};
