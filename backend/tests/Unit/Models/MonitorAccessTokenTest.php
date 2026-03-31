<?php

use App\Models\Monitor;
use App\Models\MonitorAccessToken;
use Jgss\LaravelPestScenarios\Context;
use Jgss\LaravelPestScenarios\Scenario;

$context = Context::forModel()->with(
    databaseSetup: 'create_token',
);

/**
 * ───────────────────────────────────────
 * Valid scenarios for model MonitorCheck
 * ───────────────────────────────────────
 */
describe('Models - MonitorCheck : success', function () use ($context): void {
    Scenario::forModel()->valid(
        description: 'retrieves monitor',
        context: $context,
        // --- Input ----------------------------------------------------------------
        input: fn (): Monitor => MonitorAccessToken::firstOrFail()->monitor->load('user'),
        // --- Expected output ------------------------------------------------------
        expectedOutput: fn (): Monitor => queryMonitor('monitor'),
    );
});
