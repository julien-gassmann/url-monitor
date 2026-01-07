<?php

use App\Enums\FrequencyEnum;
use App\Enums\HttpCodeEnum;
use App\Enums\PerPageEnum;
use Jgss\LaravelPestScenarios\Context;
use Jgss\LaravelPestScenarios\Scenario;

$context = Context::forApiRoute()->with(
    routeName: 'get.metadata',
);

/**
 * ───────────────────────────────────────
 * Valid scenarios for route: get.metadata
 * ───────────────────────────────────────
 */
describe('GET api/metadata/{page} : success', function () use ($context): void {
    Scenario::forApiRoute()->valid(
        description: 'returns expected metadata for page "create-monitor"',
        context: $context,
        // --- Payload --------------------------------------------------------------
        payload: ['page' => 'create-monitor'],
        // --- Expected response ----------------------------------------------------
        expectedResponse: fn () => response()->json([
            'data' => [
                'frequencies' => FrequencyEnum::forSelectDisplay(),
                'http_codes' => HttpCodeEnum::forSelectDisplay(),
            ],
        ]),
    );

    Scenario::forApiRoute()->valid(
        description: 'returns expected metadata for page "paginate-checks"',
        context: $context,
        // --- Payload --------------------------------------------------------------
        payload: ['page' => 'paginate-checks'],
        // --- Expected response ----------------------------------------------------
        expectedResponse: fn () => response()->json([
            'data' => [
                'allowed_per_page' => PerPageEnum::forSelectDisplay(),
            ],
        ]),
    );
});

/**
 * ───────────────────────────────────────
 * Invalid scenarios for route: get.metadata
 * ───────────────────────────────────────
 */
describe('GET api/metadata/{page} : failure', function () use ($context): void {
    Scenario::forApiRoute()->invalid(
        description: 'returns 404 with unknown page',
        context: $context,
        // --- Payload --------------------------------------------------------------
        payload: ['page' => 'unknown-page'],
        // --- Expected status ------------------------------------------------------
        expectedStatusCode: 404,
    );
});
