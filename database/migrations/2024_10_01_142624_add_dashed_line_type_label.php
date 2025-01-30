<?php

use App\Models\MnemoSchemaLineType;
use Illuminate\Database\Migrations\Migration;


return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        /** @var MnemoSchemaLineType $solidType */
        $solidType = MnemoSchemaLineType::query()
            ->where('type', 'dashed')
            ->first();

        $solidType->line_type_label = 'Пунктирная';
        $solidType->save();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        /** @var MnemoSchemaLineType $defaultArrowType */
        $defaultArrowType = MnemoSchemaLineType::query()
            ->where('type', 'dashed')
            ->first();

        $defaultArrowType->line_type_label = null;
        $defaultArrowType->save();
    }
};
