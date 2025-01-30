<?php

use App\Models\MethodTitleMatching;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {

    public function up(): void
    {
        MethodTitleMatching::query()
            ->insert([
                'frontend_method_title' => 'change_line_options',
                'receiver_title' => 'App\Receivers\LineOperation\ChangeLineOptionsReceiver',
                'concrete_command_title' => 'App\Commands\LineOperation\LineOperationChangeLineOptions'
            ]);
    }

    public function down(): void
    {
        MethodTitleMatching::query()
            ->where('frontend_method_title', '=', 'change_line_options')
            ->delete();
    }
};
