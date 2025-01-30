<?php

use App\Models\MnemoSchemaNodeType;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    public function up(): void
    {
        MnemoSchemaNodeType::query()
            ->insert([
                'title' => 'БДВ',
                'type' => 'bdv',
                'short_title' => 'BDV'
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        MnemoSchemaNodeType::query()
            ->where('type', '=', 'bdv')
            ->delete();
    }
};
