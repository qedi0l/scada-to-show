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
            $table->renameColumn('command_class', 'command_title');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('command_queues', function (Blueprint $table) {
            $table->renameColumn('command_title', 'command_class');
        });
    }
};
