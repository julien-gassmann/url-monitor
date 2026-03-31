<?php

use App\Http\Requests\ShowMonitorRequest;
use Jgss\LaravelPestScenarios\Context;
use Jgss\LaravelPestScenarios\Scenario;

$context = Context::forFormRequest()->with(
    formRequestClass: ShowMonitorRequest::class,
    routeName: 'monitors.show',
    routeParameters: ['monitor' => getQueryUuid('monitor')],
    actingAs: 'sanctum',
    databaseSetup: 'create_monitor',
);

/**
 * ───────────────────────────────────────
 * Valid scenarios for ShowMonitorRequest
 * ───────────────────────────────────────
 */
describe('FormRequests - ShowMonitorRequest : success', function () use ($context): void {
    Scenario::forFormRequest()->valid(
        description: 'passes with monitor\'s owner',
        context: $context,
    );
});

/**
 * ───────────────────────────────────────
 * Invalid scenarios for FormRequest ShowMonitorRequest
 * ───────────────────────────────────────
 */
describe('FormRequests - ShowMonitorRequest : failure', function () use ($context): void {
    Scenario::forFormRequest()->invalid(
        description: 'fails when acting as guest',
        context: $context->withActingAs('guest'),
        // --- Authorize ------------------------------------------------------------
        shouldAuthorize: false,
    );
});
