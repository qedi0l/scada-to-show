<?php

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
        Schema::table('mnemo_schema_nodes', function (Blueprint $table) {
            $table->integer('type_id')->after('type')->nullable();
        });

        \DB::table('mnemo_schema_nodes')->update(['type_id' => \DB::raw('type')]);

        Schema::table('mnemo_schema_nodes', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mnemo_schema_nodes', function (Blueprint $table) {
            $table->integer('type')->after('type_id')->nullable();
        });

        \DB::table('mnemo_schema_nodes')->update(['type' => \DB::raw('type_id')]);

        Schema::table('mnemo_schema_nodes', function (Blueprint $table) {
            $table->dropColumn('type_id');
        });
    }
};
