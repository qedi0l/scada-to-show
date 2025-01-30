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
                'frontend_method_title' => 'manipulate_node_commands',
                'receiver_title' => 'App\Receivers\NodeOperation\ManipulateNodeCommandsReceiver',
                'concrete_command_title' => 'App\Commands\NodeOperation\NodeOperationManipulateNodeCommands'
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        MethodTitleMatching::query()
            ->where('frontend_method_title', 'manipulate_node_commands')
            ->delete();
    }
};
