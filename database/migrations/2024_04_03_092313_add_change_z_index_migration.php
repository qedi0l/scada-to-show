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
                'frontend_method_title' => 'change_node_layer',
                'receiver_title' => 'App\Receivers\ZAxis\ChangeLayerReceiver',
                'concrete_command_title' => 'App\Commands\ZAxis\ZAxisChangeLayerCommand'
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        MethodTitleMatching::query()
            ->where('frontend_method_title', '=', 'change_node_layer')
            ->delete();
    }
};
