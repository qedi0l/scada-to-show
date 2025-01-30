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
                'frontend_method_title' => 'add_node_to_schema',
                'receiver_title' => 'App\Receivers\NodeOperation\AddNodeToSchemaReceiver',
                'concrete_command_title' => 'App\Commands\NodeOperation\NodeOperationAddNode'
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        MethodTitleMatching::query()
            ->where('frontend_method_title', '=', 'add_node_to_schema')
            ->delete();
    }
};
