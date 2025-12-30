<?php

namespace App\Console\Commands;

use App\Jobs\CheckMonitorJob;
use App\Models\Monitor;
use Illuminate\Console\Command;

class CheckMonitorsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'monitors:checks';

    /**
     * Execute the console command.
     */
    public function __invoke(): int
    {
        Monitor::dueForCheck()
            ->each(function (Monitor $monitor): void {
                dispatch(new CheckMonitorJob($monitor));
            });

        return self::SUCCESS;
    }
}
