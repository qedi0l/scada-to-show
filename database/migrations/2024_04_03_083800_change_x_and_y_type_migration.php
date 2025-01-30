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
        Schema::table('mnemo_schema_node_geometries', function (Blueprint $table) {
            $table->bigInteger('x')->change();
            $table->bigInteger('y')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mnemo_schema_node_geometries', function (Blueprint $table) {
            $table->double('x')->change();
            $table->double('y')->change();
        });
    }
};
