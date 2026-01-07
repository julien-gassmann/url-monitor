<?php

use Jgss\LaravelPestScenarios\Context;
use Jgss\LaravelPestScenarios\Scenario;
use function Pest\Laravel\assertDatabaseEmpty;
use function Pest\Laravel\assertDatabaseHas;

$context = Context::forApiRoute()->with(
    routeName: 'monitors.create',
);

/**
 * ───────────────────────────────────────
 * Valid scenarios for route: monitors.create
 * ───────────────────────────────────────
 */
describe('POST api/monitors : success', function () use ($context): void {
    Scenario::forApiRoute()->valid(
        description: 'returns 201 when creating daily monitor',
        context: $context,
        // --- Payload --------------------------------------------------------------
        payload: [
            'url' => 'https://example.com',
            'expected_http_code' => 200,
            'frequency' => 'daily',
            'user_email' => 'test@example.com',
        ],
        // --- Expected status ------------------------------------------------------
        expectedStatusCode: 201,
        // --- Expected structure ---------------------------------------------------
        expectedStructure: 'none',
        // --- Expected response ----------------------------------------------------
        expectedResponse: fn () => response()->json(),
        // --- Database assertions --------------------------------------------------
        databaseAssertions: [
            fn () => assertDatabaseHas('monitors', [
                'url' => 'https://example.com',
                'expected_http_code' => 200,
                'frequency' => 'daily',
            ]),
            fn () => assertDatabaseHas('users', [
                'user_email' => 'test@example.com',
            ]),
        ]
    );
});

/**
 * ───────────────────────────────────────
 * Invalid scenarios for route: monitors.create
 * ───────────────────────────────────────
 */
describe('POST api/monitors : failure', function () use ($context): void {
    // URL
    describe('invalid URL', function () use ($context): void {
        Scenario::forApiRoute()->invalid(
            description: 'returns 422 when URL is invalid',
            context: $context,
            // --- Payload --------------------------------------------------------------
            payload: [
                'url' => 'invalid-url',
                'expected_http_code' => 200,
                'frequency' => 'daily',
                'user_email' => 'test@example.com',
            ],
            // --- Expected structure ---------------------------------------------------
            expectedErrorStructure: ['errors' => ['url'], 'message'],
            // --- Database assertions --------------------------------------------------
            databaseAssertions: [
                fn () => assertDatabaseEmpty('monitors'),
                fn () => assertDatabaseEmpty('users'),
            ],
        );

        Scenario::forApiRoute()->invalid(
            description: 'returns 422 when URL is missing',
            context: $context,
            // --- Payload --------------------------------------------------------------
            payload: [
                'expected_http_code' => 200,
                'frequency' => 'daily',
                'user_email' => 'test@example.com',
            ],
            // --- Expected structure ---------------------------------------------------
            expectedErrorStructure: ['errors' => ['url'], 'message'],
            // --- Database assertions --------------------------------------------------
            databaseAssertions: [
                fn () => assertDatabaseEmpty('monitors'),
                fn () => assertDatabaseEmpty('users'),
            ],
        );
    });

    // HTTP code
    describe('invalid HTTP code', function () use ($context): void {
        Scenario::forApiRoute()->invalid(
            description: 'returns 422 when HTTP code is string',
            context: $context,
            // --- Payload --------------------------------------------------------------
            payload: [
                'url' => 'https://example.com',
                'expected_http_code' => 'not-valid',
                'frequency' => 'daily',
                'user_email' => 'test@example.com',
            ],
            // --- Expected structure ---------------------------------------------------
            expectedErrorStructure: ['errors' => ['expected_http_code'], 'message'],
            // --- Database assertions --------------------------------------------------
            databaseAssertions: [
                fn () => assertDatabaseEmpty('monitors'),
                fn () => assertDatabaseEmpty('users'),
            ],
        );

        Scenario::forApiRoute()->invalid(
            description: 'returns 422 when HTTP code is not existing',
            context: $context,
            // --- Payload --------------------------------------------------------------
            payload: [
                'url' => 'https://example.com',
                'expected_http_code' => 9999,
                'frequency' => 'daily',
                'user_email' => 'test@example.com',
            ],
            // --- Expected structure ---------------------------------------------------
            expectedErrorStructure: ['errors' => ['expected_http_code'], 'message'],
            // --- Database assertions --------------------------------------------------
            databaseAssertions: [
                fn () => assertDatabaseEmpty('monitors'),
                fn () => assertDatabaseEmpty('users'),
            ],
        );

        Scenario::forApiRoute()->invalid(
            description: 'returns 422 when HTTP code is missing',
            context: $context,
            // --- Payload --------------------------------------------------------------
            payload: [
                'url' => 'https://example.com',
                'frequency' => 'daily',
                'user_email' => 'test@example.com',
            ],
            // --- Expected structure ---------------------------------------------------
            expectedErrorStructure: ['errors' => ['expected_http_code'], 'message'],
            // --- Database assertions --------------------------------------------------
            databaseAssertions: [
                fn () => assertDatabaseEmpty('monitors'),
                fn () => assertDatabaseEmpty('users'),
            ],
        );
    });

    // Frequency
    describe('invalid frequency', function () use ($context): void {
        Scenario::forApiRoute()->invalid(
            description: 'returns 422 when frequency is invalid',
            context: $context,
            // --- Payload --------------------------------------------------------------
            payload: [
                'url' => 'https://example.com',
                'expected_http_code' => 200,
                'frequency' => 'invalid-frequency',
                'user_email' => 'test@example.com',
            ],
            // --- Expected structure ---------------------------------------------------
            expectedErrorStructure: ['errors' => ['frequency'], 'message'],
            // --- Database assertions --------------------------------------------------
            databaseAssertions: [
                fn () => assertDatabaseEmpty('monitors'),
                fn () => assertDatabaseEmpty('users'),
            ],
        );

        Scenario::forApiRoute()->invalid(
            description: 'returns 422 when frequency is missing',
            context: $context,
            // --- Payload --------------------------------------------------------------
            payload: [
                'url' => 'https://example.com',
                'expected_http_code' => 200,
                'user_email' => 'test@example.com',
            ],
            // --- Expected structure ---------------------------------------------------
            expectedErrorStructure: ['errors' => ['frequency'], 'message'],
            // --- Database assertions --------------------------------------------------
            databaseAssertions: [
                fn () => assertDatabaseEmpty('monitors'),
                fn () => assertDatabaseEmpty('users'),
            ],
        );
    });

    // Mail
    describe('invalid mail', function () use ($context): void {
        Scenario::forApiRoute()->invalid(
            description: 'returns 422 when mail is invalid',
            context: $context,
            // --- Payload --------------------------------------------------------------
            payload: [
                'url' => 'https://example.com',
                'expected_http_code' => 200,
                'frequency' => 'daily',
                'user_email' => 'invalid-mail',
            ],
            // --- Expected structure ---------------------------------------------------
            expectedErrorStructure: ['errors' => ['user_email'], 'message'],
            // --- Database assertions --------------------------------------------------
            databaseAssertions: [
                fn () => assertDatabaseEmpty('monitors'),
                fn () => assertDatabaseEmpty('users'),
            ],
        );

        Scenario::forApiRoute()->invalid(
            description: 'returns 422 when mail is missing',
            context: $context,
            // --- Payload --------------------------------------------------------------
            payload: [
                'url' => 'https://example.com',
                'expected_http_code' => 200,
                'frequency' => 'daily',
            ],
            // --- Expected structure ---------------------------------------------------
            expectedErrorStructure: ['errors' => ['user_email'], 'message'],
            // --- Database assertions --------------------------------------------------
            databaseAssertions: [
                fn () => assertDatabaseEmpty('monitors'),
                fn () => assertDatabaseEmpty('users'),
            ],
        );
    });

    // Combined
    describe('combined invalid fields', function () use ($context): void {
        Scenario::forApiRoute()->invalid(
            description: 'returns 422 when all fields are invalid',
            context: $context,
            // --- Payload --------------------------------------------------------------
            payload: [
                'url' => 'invalid-url',
                'expected_http_code' => 'invalid-code',
                'frequency' => 'invalid-frequency',
                'user_email' => 'invalid-mail',
            ],
            // --- Expected structure ---------------------------------------------------
            expectedErrorStructure: ['errors' => ['url', 'expected_http_code', 'frequency', 'user_email'], 'message'],
            // --- Database assertions --------------------------------------------------
            databaseAssertions: [
                fn () => assertDatabaseEmpty('monitors'),
                fn () => assertDatabaseEmpty('users'),
            ],
        );

        Scenario::forApiRoute()->invalid(
            description: 'returns 422 when all fields are missing',
            context: $context,
            // --- Expected structure ---------------------------------------------------
            expectedErrorStructure: ['errors' => ['url', 'expected_http_code', 'frequency', 'user_email'], 'message'],
            // --- Database assertions --------------------------------------------------
            databaseAssertions: [
                fn () => assertDatabaseEmpty('monitors'),
                fn () => assertDatabaseEmpty('users'),
            ],
        );
    });
});
