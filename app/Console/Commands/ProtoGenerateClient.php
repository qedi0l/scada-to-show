<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;

class ProtoGenerateClient extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'protobuf:generate:client';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate php code from protobuf files';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $process = new Process(['bash', 'Protobuf/autogen_client.sh']);
        $process->run();
        $this->info($process->getErrorOutput() ? "" : ($process->getOutput() ? "" : "Generated successful"));
    }
}
