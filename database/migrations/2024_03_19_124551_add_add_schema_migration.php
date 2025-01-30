<?php

use App\Models\MethodTitleMatching;
use Illuminate\Database\Migrations\Migration;


return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        MethodTitleMatching::query()
            ->insert([
                'frontend_method_title' => 'add_schema',
                'receiver_title' => 'App\Receivers\SchemaOperation\AddSchemaReceiver',
                'concrete_command_title' => 'App\Commands\SchemaOperation\SchemaOperationAddSchema'
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        MethodTitleMatching::query()
            ->where('frontend_method_title', '=', 'add_schema')
            ->delete();
    }
};
