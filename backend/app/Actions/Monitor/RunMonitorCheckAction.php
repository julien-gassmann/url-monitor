<?php

declare(strict_types=1);

namespace App\Actions\Monitor;

use App\Events\MonitorCheckedEvent;
use App\Models\Monitor;
use App\Services\UrlCheckService;
use Throwable;

final readonly class RunMonitorCheckAction
{
    public function __construct(
        private UrlCheckService $urlCheckService,
        private CreateMonitorCheckAction $createMonitorCheck,
        private ScheduleNextMonitorCheckAction $scheduleNextCheck,
    ) {}

    /**
     * @throws Throwable
     */
    public function handle(Monitor $monitor): void
    {
        $httpCode = $this->urlCheckService->check($monitor->url);

        $this->createMonitorCheck->handle($monitor, $httpCode);

        $this->scheduleNextCheck->handle($monitor);

        event(new MonitorCheckedEvent($monitor));
    }
}
