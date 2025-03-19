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
        Schema::create('parameter_result_particulars', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parameter_result_id')->constrained();
            $table->foreignId('parent_id')->nullable()->constrained('parameter_result_particulars', 'id')->onDelete('set null')->onUpdate('set null');
            $table->string('particular')->nullable();
            $table->float('level')->nullable();
            $table->text('description')->nullable();
            $table->float('new_index_score')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parameter_result_particulars');
    }
};
