<?php

use App\Services\DevTokenHelper;
use Illuminate\Http\JsonResponse;
use Jgss\LaravelPestScenarios\Context;
use Jgss\LaravelPestScenarios\Scenario;

use function Pest\Laravel\assertDatabaseEmpty;

uses()->beforeEach(function (): void {
    config()->set('app.keep_access_token_in_cache', true);
});

$context = Context::forApiRoute()->with(
    routeName: 'tokens.refresh',
    routeParameters: ['token' => getCachedToken()],
    databaseSetup: 'create_many_tokens',
);

/**
 * ───────────────────────────────────────
 * Valid scenarios for route: tokens.refresh
 * ───────────────────────────────────────
 */
describe('GET api/tokens/refresh/{token} : success', function () use ($context): void {
    Scenario::forApiRoute()->valid(
        description: 'returns 201 with valid access token',
        context: $context,
        // --- Expected status -------------------------------------------------------
        expectedStatusCode: 201,
        // --- Expected structure ----------------------------------------------------
        expectedStructure: 'token',
        // --- Expected response -----------------------------------------------------
        expectedResponse: fn (): JsonResponse => DevTokenHelper::response(),
        // --- Database assertions ---------------------------------------------------
        databaseAssertions: [
            fn () => assertDatabaseEmpty('personal_access_tokens'),
        ]
    );

    Scenario::forApiRoute()->valid(
        description: 'returns 201 with used access token',
        context: $context->withDatabaseSetup('create_used_token'),
        // --- Expected status -------------------------------------------------------
        expectedStatusCode: 201,
        // --- Expected structure ----------------------------------------------------
        expectedStructure: 'token',
        // --- Expected response -----------------------------------------------------
        expectedResponse: fn (): JsonResponse => DevTokenHelper::response(),
        // --- Database assertions ---------------------------------------------------
        databaseAssertions: [
            fn () => assertDatabaseEmpty('personal_access_tokens'),
        ]
    );

    Scenario::forApiRoute()->valid(
        description: 'returns 201 with expired access token',
        context: $context->withDatabaseSetup('create_expired_token'),
        // --- Expected status -------------------------------------------------------
        expectedStatusCode: 201,
        // --- Expected structure ----------------------------------------------------
        expectedStructure: 'token',
        // --- Expected response -----------------------------------------------------
        expectedResponse: fn (): JsonResponse => DevTokenHelper::response(),
        // --- Database assertions ---------------------------------------------------
        databaseAssertions: [
            fn () => assertDatabaseEmpty('personal_access_tokens'),
        ]
    );
});

/**
 * ───────────────────────────────────────
 * Invalid scenarios for route: tokens.refresh
 * ───────────────────────────────────────
 */
describe('GET api/tokens/refresh/{token} : failure', function () use ($context): void {
    Scenario::forApiRoute()->invalid(
        description: 'returns 403 with already refreshed access token',
        context: $context->withDatabaseSetup('create_refreshed_token'),
        expectedStatusCode: 403,
        // --- Expected structure ---------------------------------------------------
        expectedErrorStructure: ['message'],
        // --- Expected message -----------------------------------------------------
        expectedErrorMessage: 'This action is unauthorized.'
    );
});
