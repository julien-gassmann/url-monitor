<?php

declare(strict_types=1);

namespace App\Actions\Monitor;

use App\Actions\MonitorAccessToken\CreateMonitorAccessTokenAction;
use App\Models\Monitor;
use App\Services\UrlHealthCheck;

final readonly class RunMonitorCheckAction
{
    public function __construct(
        private CreateMonitorCheckAction $createMonitorCheck,
        private ScheduleNextMonitorCheckAction $scheduleNextCheck,
        private CreateMonitorAccessTokenAction $createAccessToken,
    ) {}

    public function handle(Monitor $monitor): void
    {
        $httpCode = UrlHealthCheck::check($monitor->url);

        $this->createMonitorCheck->handle($monitor, $httpCode);
        $this->scheduleNextCheck->handle($monitor);
        $this->createAccessToken->handle($monitor);
    }
}
