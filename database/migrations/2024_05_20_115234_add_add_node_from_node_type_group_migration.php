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
                'frontend_method_title' => 'add_node_from_node_type_group',
                'receiver_title' => 'App\Receivers\NodeOperation\AddNodeFromNodeTypeGroupReceiver',
                'concrete_command_title' => 'App\Commands\NodeOperation\NodeOperationAddNodeFromNodeTypeGroup'
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        MethodTitleMatching::query()
            ->where('frontend_method_title', '=', 'add_node_from_node_type_group')
            ->delete();
    }
};
