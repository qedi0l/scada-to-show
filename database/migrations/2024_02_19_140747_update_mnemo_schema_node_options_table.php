<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE mnemo_schema_node_options ALTER parameter_code TYPE BIGINT USING (parameter_code)::bigint");
        DB::statement("ALTER TABLE mnemo_schema_node_options ALTER hardware_code TYPE BIGINT USING (hardware_code)::bigint");

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE mnemo_schema_node_options ALTER parameter_code TYPE VARCHAR USING (parameter_code)::varchar");
        DB::statement("ALTER TABLE mnemo_schema_node_options ALTER hardware_code TYPE VARCHAR USING (hardware_code)::varchar");


    }
};
