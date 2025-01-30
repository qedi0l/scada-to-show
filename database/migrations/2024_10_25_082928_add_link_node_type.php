<?php

use App\Models\MnemoSchemaNodeType;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        MnemoSchemaNodeType::query()
            ->insert([
                'title' => 'Ссылка на Мнемосхему',
                'type' => 'link',
                'hardware_type' => 'link'
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        MnemoSchemaNodeType::query()
            ->where('type', '=', 'link')
            ->delete();
    }
};
