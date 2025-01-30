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
                'frontend_method_title' => 'delete_schema',
                'receiver_title' => 'App\Receivers\SchemaOperation\DeleteSchemaReceiver',
                'concrete_command_title' => 'App\Commands\SchemaOperation\SchemaOperationDeleteSchema'
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        MethodTitleMatching::query()
            ->where('frontend_method_title', '=', 'delete_schema')
            ->delete();
    }
};
