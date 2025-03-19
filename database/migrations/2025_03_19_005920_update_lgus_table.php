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
        Schema::table('lgus', function (Blueprint $table) {
            $table->renameColumn('city', 'lgu_type');
            $table->renameColumn('province', 'province_id');
            $table->enum('lgu_type', ['City', 'Municipality', 'Provincial'])->after('region_id')->change();
            $table->integer('province_id')->unsigned()->nullable()->change();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::table('lgus', function (Blueprint $table) {
            $table->renameColumn('lgu_type', 'city');
            $table->string('city')->after('name')->change();
            $table->renameColumn('province_id', 'province');
            $table->string('province')->nullable()->change();
            $table->dropSoftDeletes();
        });
    }
};
