<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Symfony\Component\Process\Process;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

/**
 * Class Deployer
 * @package App\Jobs
 */
class Deployer implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @return void
     */
    public function handle()
    {
        $process = new Process(['./deploy.sh']);
        $process->setEnv(['HOME' => getcwd()])->run();
    }
}
