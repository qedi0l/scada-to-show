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
        Schema::table('mnemo_schema_lines', function (Blueprint $table){
            $table->integer('source_position')->default(1);
            $table->integer('target_position')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mnemo_schema_lines', function (Blueprint $table){
            $table->dropColumn('source_position');
            $table->dropColumn('target_position');
        });
    }
};
