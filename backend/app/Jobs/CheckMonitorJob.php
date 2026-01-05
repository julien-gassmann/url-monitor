<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Actions\ProcessUrlCheckAction;
use App\Models\Monitor;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Throwable;

final class CheckMonitorJob implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public Monitor $monitor,
    ) {}

    /**
     * Execute the job.
     */
    public function handle(ProcessUrlCheckAction $runMonitorCheck): void
    {
        $runMonitorCheck->handle($this->monitor);
    }

    public function failed(Throwable $exception): void
    {
        Log::error("Check failed for monitor {$this->monitor->id}", [
            'error' => $exception->getMessage(),
            'monitor_id' => $this->monitor->id,
        ]);
    }
}
