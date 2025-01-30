<?php

use App\Models\MnemoSchemaLineArrowType;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        /** @var MnemoSchemaLineArrowType $defaultArrowType */
        $defaultArrowType = MnemoSchemaLineArrowType::query()
            ->where('arrow_type_title', 'dot')
            ->first();

        $defaultArrowType->arrow_type_label = 'Точка';
        $defaultArrowType->save();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        /** @var MnemoSchemaLineArrowType $defaultArrowType */
        $defaultArrowType = MnemoSchemaLineArrowType::query()
            ->where('arrow_type_title', 'default')
            ->first();

        $defaultArrowType->arrow_type_label = null;
        $defaultArrowType->save();
    }
};
