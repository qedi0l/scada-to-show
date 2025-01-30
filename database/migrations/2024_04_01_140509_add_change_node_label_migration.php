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
                'frontend_method_title' => 'change_node_label',
                'receiver_title' => 'App\Receivers\NodeOperation\ChangeNodeLabelReceiver',
                'concrete_command_title' => 'App\Commands\NodeOperation\NodeOperationChangeNodeLabel'
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        MethodTitleMatching::query()
            ->where('frontend_method_title', '=', 'change_node_label')
            ->delete();
    }
};
