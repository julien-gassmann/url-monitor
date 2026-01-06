<?php

use App\Models\Monitor;
use App\Models\MonitorAccessToken;
use App\Models\MonitorCheck;
use App\Models\User;
use Jgss\LaravelPestScenarios\Context;
use Jgss\LaravelPestScenarios\Scenario;

$context = Context::forModel()->with(
    databaseSetup: 'create_checks',
);

/**
 * ───────────────────────────────────────
 * Valid scenarios for model User
 * ───────────────────────────────────────
 */
describe('Models - User : success', function () use ($context): void {
    Scenario::forModel()->valid(
        description: 'retrieves monitors',
        context: $context,
        input: fn () => User::firstOrFail()->monitors()->get(),
        expectedOutput: fn () => Monitor::all(),
    );

    Scenario::forModel()->valid(
        description: 'retrieves monitor access tokens',
        context: $context->withDatabaseSetup('create_token'),
        input: fn () => User::firstOrFail()->monitorAccessTokens()->pluck('monitor_access_tokens.id'),
        expectedOutput: fn () => MonitorAccessToken::pluck('id'),
    );

    Scenario::forModel()->valid(
        description: 'retrieves monitor checks',
        context: $context->withDatabaseSetup('create_token'),
        input: fn () => User::firstOrFail()->monitorChecks()->pluck('monitor_checks.id'),
        expectedOutput: fn () => MonitorCheck::pluck('id'),
    );
});
