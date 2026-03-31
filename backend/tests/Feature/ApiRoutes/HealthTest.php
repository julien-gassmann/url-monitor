<?php

use Jgss\LaravelPestScenarios\Context;
use Jgss\LaravelPestScenarios\Scenario;

$context = Context::forApiRoute()->with(
    routeName: 'health',
);

/**
 * ───────────────────────────────────────
 * Valid scenarios for route: health
 * ───────────────────────────────────────
 */
describe('GET api/health : success', function () use ($context): void {
    Scenario::forApiRoute()->valid(
        description: 'returns status 200 when UP',
        context: $context,
        // --- Expected response ----------------------------------------------------
        expectedStructure: 'none',
        expectedResponse: fn () => response()->json(['status' => 'ok']),
    );
});
