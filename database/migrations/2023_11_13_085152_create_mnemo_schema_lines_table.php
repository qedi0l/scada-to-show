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
        Schema::create('mnemo_schema_lines', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('schema_id');
            $table->bigInteger('first_node');
            $table->bigInteger('second_node');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mnemo_schema_lines');
    }
};
