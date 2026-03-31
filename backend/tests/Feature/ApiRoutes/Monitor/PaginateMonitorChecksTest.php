<?php

use App\Http\Resources\MonitorCheckResource;
use App\Models\MonitorCheck;
use Jgss\LaravelPestScenarios\Context;
use Jgss\LaravelPestScenarios\Scenario;

use function Jgss\LaravelPestScenarios\databaseSetup;
use function Jgss\LaravelPestScenarios\queryCollection;
use function Jgss\LaravelPestScenarios\queryId;
use function Pest\Laravel\getJson;

$context = Context::forApiRoute()->with(
    routeName: 'monitors.checks.paginate',
    routeParameters: ['monitor' => getQueryUuid('monitor')],
    actingAs: 'user',
    databaseSetup: ['create_checks'],
);

/**
 * ───────────────────────────────────────
 * Valid scenarios for route: monitors.checks.paginate
 * ───────────────────────────────────────
 */
describe('GET api/monitors/{monitor}/checks : success', function () use ($context): void {
    describe('Required parameters', function () use ($context): void {
        Scenario::forApiRoute()->valid(
            description: "returns 200 when 'page' = 1 and 'per_page' = 10",
            context: $context,
            // --- Payload --------------------------------------------------------------
            payload: [
                'page' => '1',
                'per_page' => '10',
            ],
            // --- Expected structure ---------------------------------------------------
            expectedStructure: 'pagination',
            // --- Expected response ----------------------------------------------------
            expectedResponse: fn () => MonitorCheckResource::collection(
                MonitorCheck::where('monitor_id', queryId('monitor'))->paginate(10)
            )->response(),
        );

        Scenario::forApiRoute()->valid(
            description: "returns 200 when 'page' = 1 and 'per_page' = -1",
            context: $context,
            // --- Payload --------------------------------------------------------------
            payload: [
                'page' => '1',
                'per_page' => '-1',
            ],
            // --- Expected structure ---------------------------------------------------
            expectedStructure: 'pagination',
            // --- Expected response ----------------------------------------------------
            expectedResponse: fn () => MonitorCheckResource::collection(
                MonitorCheck::where('monitor_id', queryId('monitor'))
                    ->paginate(queryCollection('checks')->count())
            )->response(),
        );
    });

    describe('Sort parameter', function () use ($context): void {
        foreach (MonitorCheck::allowedSorts() as $sort) {
            $direction = str_starts_with($sort, '-') ? 'desc' : 'asc';
            $column = ltrim($sort, '-');

            Scenario::forApiRoute()->valid(
                description: 'returns 200 when "sort" = "'.$sort.'"',
                context: $context,
                // --- Payload --------------------------------------------------------------
                payload: [
                    'page' => '1',
                    'per_page' => '10',
                    'sort' => $sort,
                ],
                // --- Expected structure ---------------------------------------------------
                expectedStructure: 'pagination',
                // --- Expected response ----------------------------------------------------
                expectedResponse: fn () => MonitorCheckResource::collection(
                    MonitorCheck::where('monitor_id', queryId('monitor'))
                        ->orderBy($column, $direction)
                        ->paginate(10)
                )->response(),
            );
        }
    });
});

/**
 * ───────────────────────────────────────
 * Invalid scenarios for route: monitors.checks.paginate
 * ───────────────────────────────────────
 */
describe('GET api/monitors/{monitor}/checks : failure', function () use ($context): void {
    describe('Missing required parameters', function () use ($context): void {
        Scenario::forApiRoute()->invalid(
            description: "returns 400 when 'page' and 'per_page' are missing",
            context: $context,
            // --- Expected structure ---------------------------------------------------
            expectedErrorStructure: ['errors' => ['page', 'per_page']],
        );

        Scenario::forApiRoute()->invalid(
            description: "returns 400 when 'per_page' is missing",
            context: $context,
            // --- Payload --------------------------------------------------------------
            payload: ['page' => '1'],
            // --- Expected structure ---------------------------------------------------
            expectedErrorStructure: ['errors' => ['per_page']],
        );
    });

    describe('Invalid parameters', function () use ($context): void {
        Scenario::forApiRoute()->invalid(
            description: "returns 400 when 'page' and 'per_page' have invalid values",
            context: $context,
            // --- Payload --------------------------------------------------------------
            payload: [
                'page' => '-1',
                'per_page' => '1000',
            ],
            // --- Expected structure ---------------------------------------------------
            expectedErrorStructure: ['errors' => ['page', 'per_page']],
        );

        Scenario::forApiRoute()->invalid(
            description: "returns 400 when 'sort' has invalid value",
            context: $context,
            // --- Payload --------------------------------------------------------------
            payload: [
                'page' => '1',
                'per_page' => '10',
                'sort' => 'invalid',
            ],
            // --- Expected structure ---------------------------------------------------
            expectedErrorStructure: ['errors' => ['sort']],
        );
    });

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

        it("returns 403 when user requests for another user's monitor checks", function (): void {
            // Arrange: Create monitors and users
            databaseSetup('create_checks');
            databaseSetup('create_other_checks');

            // Arrange: Create corresponding token
            $monitor = queryMonitor('monitor');
            $token = $monitor->user->createToken('test', ['view-monitor:'.$monitor->id])->plainTextToken;

            // Act: Request other monitor as unauthorized user
            $response = getJson(
                '/api/monitors/'.queryUuid('other_monitor').'/checks',
                ['Authorization' => 'Bearer '.$token]
            );

            // Assert: Status is 403 - Forbidden
            $response->assertStatus(403);
        });
    });
});
