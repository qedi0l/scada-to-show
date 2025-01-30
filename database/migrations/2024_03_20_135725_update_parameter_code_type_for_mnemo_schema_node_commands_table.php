<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE mnemo_schema_node_commands ALTER parameter_code TYPE BIGINT USING (parameter_code)::bigint");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE mnemo_schema_node_commands ALTER parameter_code TYPE VARCHAR USING (parameter_code)::varchar");
    }
};
