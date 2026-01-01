<?php

declare(strict_types=1);

namespace App\Actions\Monitor;

use App\Actions\MonitorAccessToken\CreateMonitorAccessTokenAction;
use App\Models\Monitor;
use App\Services\UrlHealthCheck;
use Throwable;

final readonly class RunMonitorCheckAction
{
    public function __construct(
        private UrlHealthCheck $urlHealthCheck,
        private CreateMonitorCheckAction $createMonitorCheck,
        private ScheduleNextMonitorCheckAction $scheduleNextCheck,
        private CreateMonitorAccessTokenAction $createAccessToken,
    ) {}

    /**
     * @throws Throwable
     */
    public function handle(Monitor $monitor): void
    {
        $httpCode = $this->urlHealthCheck->check($monitor->url);

        $this->createMonitorCheck->handle($monitor, $httpCode);
        $this->scheduleNextCheck->handle($monitor);
        $this->createAccessToken->handle($monitor);
    }
}
