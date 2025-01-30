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
        Schema::table('mnemo_schema_line_arrow_types', function (Blueprint $table) {
            $table->string('arrow_type_label')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mnemo_schema_line_arrow_types', function (Blueprint $table) {
            $table->dropColumn('arrow_type_label');
        });
    }
};
