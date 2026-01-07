<?php

use App\Models\Monitor;
use App\Models\MonitorCheck;
use Jgss\LaravelPestScenarios\Context;
use Jgss\LaravelPestScenarios\Scenario;

$context = Context::forModel()->with(
    databaseSetup: 'create_checks',
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
        input: fn (): Monitor => MonitorCheck::firstOrFail()->monitor->load('user'),
        // --- Expected output ------------------------------------------------------
        expectedOutput: fn (): Monitor => queryMonitor('monitor'),
    );

    describe('UsesSpatieQueryBuilder trait', function () use ($context): void {
        Scenario::forModel()->valid(
            description: 'provides allowed attributes for filter',
            context: $context,
            // --- Input ----------------------------------------------------------------
            input: fn (): array => MonitorCheck::allowedFilters(),
            // --- Expected output ------------------------------------------------------
            expectedOutput: fn (): array => [
                'status',
                'http_code',
            ],
        );

        Scenario::forModel()->valid(
            description: 'provides allowed attributes for sorting',
            context: $context,
            // --- Input ----------------------------------------------------------------
            input: fn (): array => MonitorCheck::allowedSorts(),
            // --- Expected output ------------------------------------------------------
            expectedOutput: fn (): array => [
                'status', '-status',
                'http_code', '-http_code',
                'checked_at', '-checked_at',
            ],
        );

        Scenario::forModel()->valid(
            description: 'provides allowed scopes for filter',
            context: $context,
            // --- Input ----------------------------------------------------------------
            input: fn (): array => MonitorCheck::allowedScopes(),
            // --- Expected output ------------------------------------------------------
            expectedOutput: fn (): array => [],
        );

        Scenario::forModel()->valid(
            description: 'provides allowed relations to include',
            context: $context,
            // --- Input ----------------------------------------------------------------
            input: fn (): array => MonitorCheck::allowedIncludes(),
            // --- Expected output ------------------------------------------------------
            expectedOutput: fn (): array => [],
        );
    });
});
