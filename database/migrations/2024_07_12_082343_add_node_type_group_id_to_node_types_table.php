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
        Schema::table('mnemo_schema_node_types', function (Blueprint $table) {
            $table->bigInteger('node_type_group_id')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mnemo_schema_node_types', function (Blueprint $table) {
            $table->dropColumn('node_type_group_id');
        });
    }
};
