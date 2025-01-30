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
                'title' => 'Текст',
                'type' => 'text',
                'short_title' => 'TEXT'
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        MnemoSchemaNodeType::query()
            ->where('type', '=', 'text')
            ->delete();
    }
};
