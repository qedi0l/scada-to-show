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
        Schema::table('mnemo_schema_node_commands', function (Blueprint $table) {
            $table->dropUnique("mnemo_schema_node_commands_chosen_command_node_id_unique");
            $table->unique(['parameter_code', 'node_id', 'hardware_code']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mnemo_schema_node_commands', function (Blueprint $table) {
            $table->dropUnique(['parameter_code', 'node_id', 'hardware_code']);
            $table->renameColumn('parameter_code', 'chosen_command');
        });
        Schema::table('mnemo_schema_node_commands', function (Blueprint $table) {
            $table->unique(['chosen_command', 'node_id']);
            $table->renameColumn('chosen_command', 'parameter_code');
        });

    }
};
