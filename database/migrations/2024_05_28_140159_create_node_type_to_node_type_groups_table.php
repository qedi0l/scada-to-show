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
        Schema::create('node_type_to_node_type_groups', function (Blueprint $table) {
            $table->unsignedBigInteger('node_type_id');
            $table->unsignedBigInteger('node_type_group_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('node_type_to_node_type_groups');
    }
};
