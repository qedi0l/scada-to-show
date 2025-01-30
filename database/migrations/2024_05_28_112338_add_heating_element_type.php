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
                'title' => 'Нагревательный элемент',
                'type' => 'heating-element',
                'short_title' => 'HEATING_ELEMENT'
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        MnemoSchemaNodeType::query()
            ->where('type', '=', 'heating-element')
            ->delete();
    }
};
