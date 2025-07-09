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
        Schema::table('periods', function (Blueprint $table) {
            try {
                $table->dropForeign(['questionnaire_id']);
            } catch (\Illuminate\Database\QueryException $e) {
                // Foreign key doesn't exist, ignore
            }
            
            $table->renameColumn('questionnaire_id', 'questionnaire_tree_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::table('periods', function (Blueprint $table) {
            $table->renameColumn('questionnaire_tree_id', 'questionnaire_id');
         });
    }
};
