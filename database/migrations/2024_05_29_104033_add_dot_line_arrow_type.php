<?php

use App\Models\MnemoSchemaLineArrowType;
use Illuminate\Database\Migrations\Migration;


return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        MnemoSchemaLineArrowType::query()
            ->insert([
                'arrow_type_title' => 'dot'
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        MnemoSchemaLineArrowType::query()
            ->where('arrow_type_title', '=', 'dot')
            ->delete();
    }
};
