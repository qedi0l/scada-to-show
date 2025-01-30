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
                'title' => 'Открыто-Закрыто',
                'type' => 'open-closed',
                'short_title' => 'OPEN_CLOSED'
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        MnemoSchemaNodeType::query()
            ->where('type', '=', 'open-closed')
            ->delete();
    }
};
