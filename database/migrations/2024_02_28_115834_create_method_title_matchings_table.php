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
        Schema::create('method_title_matchings', function (Blueprint $table) {
            $table->id();
            $table->string('frontend_method_title');
            $table->string('receiver_title');
            $table->string('concrete_command_title');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('method_title_matchings');
    }
};
