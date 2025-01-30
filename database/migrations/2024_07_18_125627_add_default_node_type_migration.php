<?php

use App\Models\MnemoSchemaNodeType;
use Illuminate\Database\Migrations\Migration;


return new class extends Migration {

    public function up(): void
    {
        MnemoSchemaNodeType::query()
            ->insert([
                'title' => 'Default',
                'type' => 'default',
                'hardware_type' => 'DEFAULT'
            ]);
    }

    public function down(): void
    {
        MnemoSchemaNodeType::query()
            ->where('type', 'default')
            ->delete();
    }
};
