<?php

declare(strict_types=1);

namespace App\Actions;

use App\Actions\MonitorAccessToken\CreateMonitorAccessTokenAction;
use App\Actions\MonitorAccessToken\InvalidateMonitorAccessTokensAction;
use App\Models\MonitorAccessToken;
use Illuminate\Support\Facades\DB;
use Throwable;

final readonly class ProcessAccessTokenRefresh
{
    public function __construct(
        private InvalidateMonitorAccessTokensAction $invalidatePreviousTokens,
        private CreateMonitorAccessTokenAction $createAccessToken,
    ) {}

    /**
     * @throws Throwable
     */
    public function handle(MonitorAccessToken $monitorAccessToken): void
    {
        DB::transaction(function () use ($monitorAccessToken): void {
            $monitor = $monitorAccessToken->monitor;

            $this->invalidatePreviousTokens->onRefresh($monitor);
            $this->createAccessToken->handle($monitorAccessToken->monitor);
        });
    }
}
