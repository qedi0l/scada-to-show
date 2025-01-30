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
        Schema::table('mnemo_schema_node_type_groups', function (Blueprint $table){
            $table->string('description')->nullable();
            $table->string('short_title')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mnemo_schema_node_type_groups', function (Blueprint $table){
            $table->dropColumn('description');
            $table->dropColumn('short_title');
        });
    }
};
