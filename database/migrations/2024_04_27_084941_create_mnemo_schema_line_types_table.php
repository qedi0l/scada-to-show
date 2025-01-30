<?php

use App\Models\MnemoSchemaLineType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('mnemo_schema_line_types', function (Blueprint $table) {
            $table->id();
            $table->string('type');
        });

        MnemoSchemaLineType::query()
            ->insert([
                'type' => 'solid'
            ]);

        MnemoSchemaLineType::query()
            ->insert([
                'type' => 'dashed'
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mnemo_schema_line_types');
    }
};
