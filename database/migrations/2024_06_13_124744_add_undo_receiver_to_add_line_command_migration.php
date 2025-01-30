<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */

    public function up()
    {
        DB::table('method_title_matchings')
            ->where('frontend_method_title', 'add_line')
            ->update(['undo_receiver_title' => 'App\Receivers\LineOperation\DeleteLineReceiver']);
    }

    public function down()
    {
        DB::table('method_title_matchings')
            ->where('frontend_method_title', 'add_line')
            ->update(['undo_receiver_title' => null]);
    }
};
