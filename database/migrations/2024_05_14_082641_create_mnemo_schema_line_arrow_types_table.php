<?php

use App\Models\MnemoSchemaLineArrowType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('mnemo_schema_line_arrow_types', function (Blueprint $table) {
            $table->id();
            $table->string('arrow_type_title');
        });

        MnemoSchemaLineArrowType::query()
            ->insert([
                'arrow_type_title' => 'default'
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mnemo_schema_line_arrow_types');
    }
};
