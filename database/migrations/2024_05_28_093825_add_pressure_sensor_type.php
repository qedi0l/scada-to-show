<?php

use App\Models\MnemoSchemaNodeType;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        MnemoSchemaNodeType::query()
            ->insert([
                'title' => 'Датчик давления',
                'type' => 'pressure-sensor',
                'short_title' => 'PRESSURE_SENSOR'
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        MnemoSchemaNodeType::query()
            ->where('type', '=', 'pressure-sensor')
            ->delete();
    }
};
