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
                'frontend_method_title' => 'delete_line',
                'receiver_title' => 'App\Receivers\LineOperation\DeleteLineReceiver',
                'concrete_command_title' => 'App\Commands\LineOperation\LineOperationDeleteLine'
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        MethodTitleMatching::query()
            ->where('frontend_method_title', '=', 'delete_line')
            ->delete();
    }
};
