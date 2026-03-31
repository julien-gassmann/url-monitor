<?php

use App\Models\Monitor;
use App\Models\MonitorAccessToken;
use App\Models\MonitorCheck;
use App\Models\User;
use Jgss\LaravelPestScenarios\Context;
use Jgss\LaravelPestScenarios\Scenario;

use function Jgss\LaravelPestScenarios\queryId;

$context = Context::forModel()->with(
    databaseSetup: 'create_many_tokens',
);

/**
 * ───────────────────────────────────────
 * Valid scenarios for model Monitor
 * ───────────────────────────────────────
 */
describe('Models - Monitor : success', function () use ($context): void {
    Scenario::forModel()->valid(
        description: 'retrieves user',
        context: $context,
        // --- Input ----------------------------------------------------------------
        input: fn (): User => queryMonitor('monitor')->user()->firstOrFail(),
        // --- Expected output ------------------------------------------------------
        expectedOutput: fn () => User::firstOrFail(),
    );

    Scenario::forModel()->valid(
        description: 'retrieves monitor access tokens',
        context: $context,
        // --- Input ----------------------------------------------------------------
        input: fn () => queryMonitor('monitor')->accessTokens()->pluck('id'),
        // --- Expected output ------------------------------------------------------
        expectedOutput: fn () => MonitorAccessToken::where('monitor_id', queryId('monitor'))->pluck('id'),
    );

    Scenario::forModel()->valid(
        description: 'retrieves monitor checks',
        context: $context,
        // --- Input ----------------------------------------------------------------
        input: fn () => queryMonitor('monitor')->checks()->pluck('id'),
        // --- Expected output ------------------------------------------------------
        expectedOutput: fn () => MonitorCheck::where('monitor_id', queryId('monitor'))->pluck('id'),
    );

    Scenario::forModel()->valid(
        description: 'retrieves monitors due for checks',
        context: $context,
        // --- Input ----------------------------------------------------------------
        input: fn () => Monitor::dueForCheck()->get(),
        // --- Expected output ------------------------------------------------------
        expectedOutput: fn () => Monitor::whereNotNull('next_check_at')
            ->where('next_check_at', '<=', now())
            ->get(),
    );
});
