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
        Schema::create('mnemo_schema_node_appearances', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('node_id')->unique();
            $table->integer('width');
            $table->integer('height');
            $table->text('svg_url');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mnemo_schema_node_appearances');
    }
};
