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
        Schema::table('command_queues', function (Blueprint $table) {
            $table->bigInteger('schema_id')
                ->nullable()
                ->after('command_json');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('command_queues', function (Blueprint $table) {
            $table->dropColumn('schema_id');
        });
    }
};
