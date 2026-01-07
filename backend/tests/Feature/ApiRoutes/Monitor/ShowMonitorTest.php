<?php

use App\Http\Resources\MonitorResource;
use Jgss\LaravelPestScenarios\Context;
use Jgss\LaravelPestScenarios\Scenario;

use function Jgss\LaravelPestScenarios\databaseSetup;
use function Jgss\LaravelPestScenarios\queryModel;
use function Pest\Laravel\getJson;

$context = Context::forApiRoute()->with(
    routeName: 'monitors.show',
    routeParameters: ['monitor' => getQueryUuid('monitor')],
    actingAs: 'user',
    databaseSetup: ['create_monitor'],
);

/**
 * ───────────────────────────────────────
 * Valid scenarios for route: monitors.show
 * ───────────────────────────────────────
 */
describe('GET api/monitors/{monitor} : success', function () use ($context): void {
    Scenario::forApiRoute()->valid(
        description: 'returns 200 when user request his monitor',
        context: $context,
        // --- Expected structure ---------------------------------------------------
        expectedStructure: 'resource',
        // --- Expected response ----------------------------------------------------
        expectedResponse: fn () => MonitorResource::make(queryModel('monitor'))->response(),
    );
});

/**
 * ───────────────────────────────────────
 * Invalid scenarios for route: monitors.show
 * ───────────────────────────────────────
 */
describe('GET api/monitors/{monitor} : failure', function () use ($context): void {
    describe('Invalid Resource', function () use ($context): void {
        Scenario::forApiRoute()->invalid(
            description: 'returns 404 when requesting non-existent uuid',
            context: $context->withRouteParameters(['monitor' => 'non-existing']),
            // --- Expected status ------------------------------------------------------
            expectedStatusCode: 404,
        );
    });

    describe('Invalid authorizations', function () use ($context): void {
        Scenario::forApiRoute()->invalid(
            description: "returns 401 and 'unauthenticated' message when user is not logged in",
            context: $context->withActingAs('guest'),
            // --- Expected status ------------------------------------------------------
            expectedStatusCode: 401,
            // --- Expected message -----------------------------------------------------
            expectedErrorMessage: 'Unauthenticated.',
        );

        it("returns 403 when user requests for another user's monitor", function (): void {
            // Arrange: Create monitors and users
            databaseSetup('create_monitor');
            databaseSetup('create_other_monitor');

            // Arrange: Create corresponding token
            $monitor = queryMonitor('monitor');
            $token = $monitor->user->createToken('test', ['view-monitor:'.$monitor->id])->plainTextToken;

            // Act: Request other monitor as unauthorized user
            $response = getJson(
                '/api/monitors/'.queryUuid('other_monitor'),
                ['Authorization' => 'Bearer '.$token]
            );

            // Assert: Status is 403 - Forbidden
            $response->assertStatus(403);
        });
    });
});
