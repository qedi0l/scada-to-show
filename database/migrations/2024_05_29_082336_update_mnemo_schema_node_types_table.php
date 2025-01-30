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
        Schema::table('mnemo_schema_node_types', function (Blueprint $table){
            $table->dropColumn('node_type_group_id');
            $table->renameColumn('short_title', 'hardware_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mnemo_schema_node_types', function (Blueprint $table){
            $table->unsignedBigInteger('node_type_group_id')->nullable();
            $table->renameColumn('hardware_type', 'short_title');
        });
    }
};
