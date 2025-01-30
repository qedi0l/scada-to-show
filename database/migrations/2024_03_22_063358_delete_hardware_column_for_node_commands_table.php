<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('mnemo_schema_node_commands', function (Blueprint $table) {
            $table->dropUnique("mnemo_schema_node_commands_parameter_code_node_id_hardware_code");
            $table->dropColumn("hardware_code");
            $table->unique(['node_id', 'parameter_code']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mnemo_schema_node_commands', function (Blueprint $table) {
            $table->bigInteger("hardware_code");
        });

        Schema::table('mnemo_schema_node_commands', function (Blueprint $table) {
            $table->unique(['parameter_code','node_id','hardware_code']);
        });
    }

};
