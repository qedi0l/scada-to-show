<?php

namespace App\Console\Commands;

use App\Models\CommandQueue;
use Illuminate\Console\Command;

class ClearQueueTable extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'queue-table:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Truncates CommandQueue table';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $deletedCount = CommandQueue::query()->where('created_at', '<=', now()->subHour())->delete();

        $this->info("Удалено записей: $deletedCount");
    }
}
