<?php

namespace App\Jobs;

use App\Actions\Monitor\RunMonitorCheckAction;
use App\Models\Monitor;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Throwable;

final class CheckMonitorJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public Monitor $monitor,
    ) {}

    /**
     * Execute the job.
     *
     * @throws Throwable
     */
    public function handle(RunMonitorCheckAction $runMonitorCheck): void
    {
        $runMonitorCheck->handle($this->monitor);
    }

    /**
     * Gestion des erreurs
     */
    public function failed(Throwable $exception): void
    {
        Log::error("Check failed for monitor {$this->monitor->id}", [
            'error' => $exception->getMessage(),
            'monitor_id' => $this->monitor->id,
        ]);
    }
}
