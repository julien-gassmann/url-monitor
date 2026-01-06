<?php

use App\Enums\InvalidReasonEnum;
use Jgss\LaravelPestScenarios\Context;
use Jgss\LaravelPestScenarios\Scenario;

use function Jgss\LaravelPestScenarios\databaseSetup;
use function Jgss\LaravelPestScenarios\jsonStructure;
use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\assertDatabaseEmpty;
use function Pest\Laravel\getJson;

uses()->beforeEach(function (): void {
    config()->set('app.keep_access_token_in_cache', true);
});

$context = Context::forApiRoute()->with(
    routeName: 'tokens.verify',
    routeParameters: ['token' => getCachedToken()],
    databaseSetup: 'create_token',
);

/**
 * ───────────────────────────────────────
 * Valid scenarios for route: tokens.verify
 * ───────────────────────────────────────
 */
describe('GET api/tokens/verify/{token} : success', function (): void {
    it('returns 200 with valid access token', function (): void {
        // Arrange: Create monitor access token
        databaseSetup('create_token');
        $token = getCachedToken();

        // Act: Call verify token endpoint
        $response = getJson('/api/tokens/verify/'.$token());

        // Assert: Response status and structure
        $expectedStructure = ['data' => jsonStructure('verify_token_result')];
        $response->assertOk()
            ->assertJsonStructure($expectedStructure);

        // Assert: Response content contains expected values
        expect($response['data'])
            ->toHaveKey('is_valid', true)
            ->toHaveKey('reason', null)
            ->toHaveKey('monitor_uuid', queryUuid('monitor'))
            ->toHaveKey('bearer')
            ->toHaveKey('bearer.token')->not()->toBeNull()
            ->toHaveKey('bearer.expires_at')->not()->toBeNull();

        // Assert: Database has Sanctum token
        assertDatabaseCount('personal_access_tokens', 1);
    });
});

/**
 * ───────────────────────────────────────
 * Invalid scenarios for route: tokens.verify
 * ───────────────────────────────────────
 */
describe('GET api/tokens/verify/{token} : failure', function () use ($context): void {
    Scenario::forApiRoute()->valid(
        description: 'returns 401 with used access token',
        context: $context->withDatabaseSetup('create_used_token'),
        // --- Expected status -------------------------------------------------------
        expectedStatusCode: 401,
        // --- Expected structure ----------------------------------------------------
        expectedStructure: fn (): array => ['errors' => jsonStructure('verify_token_result')],
        // --- Expected response -----------------------------------------------------
        expectedResponse: fn () => response()->json([
            'errors' => [
                'is_valid' => false,
                'reason' => InvalidReasonEnum::AlreadyUsed->label(),
                'monitor_uuid' => null,
                'bearer' => ['token' => null, 'expires_at' => null],
            ],
        ]),
        // --- Database Assertions ----------------------------------------------------
        databaseAssertions: [
            fn () => assertDatabaseEmpty('personal_access_tokens'),
        ]
    );

    Scenario::forApiRoute()->valid(
        description: 'returns 401 with refreshed access token',
        context: $context->withDatabaseSetup('create_refreshed_token'),
        // --- Expected status -------------------------------------------------------
        expectedStatusCode: 401,
        // --- Expected structure ----------------------------------------------------
        expectedStructure: fn (): array => ['errors' => jsonStructure('verify_token_result')],
        // --- Expected response -----------------------------------------------------
        expectedResponse: fn () => response()->json([
            'errors' => [
                'is_valid' => false,
                'reason' => InvalidReasonEnum::AlreadyUsed->label(),
                'monitor_uuid' => null,
                'bearer' => ['token' => null, 'expires_at' => null],
            ],
        ]),
        // --- Database Assertions ----------------------------------------------------
        databaseAssertions: [
            fn () => assertDatabaseEmpty('personal_access_tokens'),
        ]
    );

    Scenario::forApiRoute()->valid(
        description: 'returns 401 with expired access token',
        context: $context->withDatabaseSetup('create_expired_token'),
        // --- Expected status -------------------------------------------------------
        expectedStatusCode: 401,
        // --- Expected structure ----------------------------------------------------
        expectedStructure: fn (): array => ['errors' => jsonStructure('verify_token_result')],
        // --- Expected response -----------------------------------------------------
        expectedResponse: fn () => response()->json([
            'errors' => [
                'is_valid' => false,
                'reason' => InvalidReasonEnum::Expired->label(),
                'monitor_uuid' => null,
                'bearer' => ['token' => null, 'expires_at' => null],
            ],
        ]),
        // --- Database Assertions ----------------------------------------------------
        databaseAssertions: [
            fn () => assertDatabaseEmpty('personal_access_tokens'),
        ]
    );

    Scenario::forApiRoute()->valid(
        description: 'returns 404 with not found access token',
        context: $context->withRouteParameters(['token' => 'non-existing-token']),
        // --- Expected status -------------------------------------------------------
        expectedStatusCode: 404,
        // --- Expected structure ----------------------------------------------------
        expectedStructure: fn (): array => ['errors' => jsonStructure('verify_token_result')],
        // --- Expected response -----------------------------------------------------
        expectedResponse: fn () => response()->json([
            'errors' => [
                'is_valid' => false,
                'reason' => InvalidReasonEnum::NotFound->label(),
                'monitor_uuid' => null,
                'bearer' => ['token' => null, 'expires_at' => null],
            ],
        ]),
        // --- Database Assertions ----------------------------------------------------
        databaseAssertions: [
            fn () => assertDatabaseEmpty('personal_access_tokens'),
        ]
    );
});
