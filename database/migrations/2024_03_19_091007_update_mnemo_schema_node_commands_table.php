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
            $table->renameColumn('command_signal_id', 'parameter_code');
            $table->integer('hardware_code')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mnemo_schema_node_commands', function (Blueprint $table) {
            $table->renameColumn('parameter_code', 'command_signal_id');
            $table->dropColumn('hardware_code');
        });
    }
};
