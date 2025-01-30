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
        Schema::table('mnemo_schema_line_appearances', function (Blueprint $table) {
            $table->unsignedInteger('opacity')->default(100)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mnemo_schema_line_appearances', function (Blueprint $table) {
            $table->unsignedInteger('opacity')->default(0)->change();
        });
    }
};
