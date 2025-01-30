<?php

use App\Models\MnemoSchemaNodeType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('mnemo_schema_node_types', function (Blueprint $table) {
            $table->boolean('service_type')->default(false);
        });

        MnemoSchemaNodeType::query()
            ->where('short_title', '=', 'DISPLAYING_VALUE')
            ->update(['service_type' => true]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mnemo_schema_node_types', function (Blueprint $table) {
            $table->dropColumn('service_type');
        });
    }
};
