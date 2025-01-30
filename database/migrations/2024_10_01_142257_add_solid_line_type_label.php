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
            ->where('type', 'solid')
            ->first();

        $solidType->line_type_label = 'Сплошная';
        $solidType->save();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        /** @var MnemoSchemaLineType $defaultArrowType */
        $defaultArrowType = MnemoSchemaLineType::query()
            ->where('type', 'solid')
            ->first();

        $defaultArrowType->line_type_label = null;
        $defaultArrowType->save();
    }
};
