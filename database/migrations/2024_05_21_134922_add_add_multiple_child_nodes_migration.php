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
                'frontend_method_title' => 'add_multiple_child_nodes',
                'receiver_title' => 'App\Receivers\NodeOperation\AddMultipleChildNodesReceiver',
                'concrete_command_title' => 'App\Commands\NodeOperation\NodeOperationAddMultipleChildNodes'
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        MethodTitleMatching::query()
            ->where('frontend_method_title', 'add_multiple_child_nodes')
            ->delete();
    }
};
