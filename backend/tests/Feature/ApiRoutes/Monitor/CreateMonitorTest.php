<?php

use Jgss\LaravelPestScenarios\Context;
use Jgss\LaravelPestScenarios\Scenario;

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
        payload: [
            'url' => 'https://example.com',
            'expected_http_code' => 200,
            'frequency' => 'daily',
            'user_email' => 'test@example.com',
        ],
        expectedStatusCode: 201,
        expectedStructure: 'none',
        expectedResponse: fn () => response()->json(),
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
            payload: [
                'url' => 'invalid-url',
                'expected_http_code' => 200,
                'frequency' => 'daily',
                'user_email' => 'test@example.com',
            ],
            expectedErrorStructure: ['errors' => ['url'], 'message']
        );

        Scenario::forApiRoute()->invalid(
            description: 'returns 422 when URL is missing',
            context: $context,
            payload: [
                'expected_http_code' => 200,
                'frequency' => 'daily',
                'user_email' => 'test@example.com',
            ],
            expectedErrorStructure: ['errors' => ['url'], 'message']
        );
    });

    // HTTP code
    describe('invalid HTTP code', function () use ($context): void {
        Scenario::forApiRoute()->invalid(
            description: 'returns 422 when HTTP code is string',
            context: $context,
            payload: [
                'url' => 'https://example.com',
                'expected_http_code' => 'not-valid',
                'frequency' => 'daily',
                'user_email' => 'test@example.com',
            ],
            expectedErrorStructure: ['errors' => ['expected_http_code'], 'message']
        );

        Scenario::forApiRoute()->invalid(
            description: 'returns 422 when HTTP code is not existing',
            context: $context,
            payload: [
                'url' => 'https://example.com',
                'expected_http_code' => 9999,
                'frequency' => 'daily',
                'user_email' => 'test@example.com',
            ],
            expectedErrorStructure: ['errors' => ['expected_http_code'], 'message']
        );

        Scenario::forApiRoute()->invalid(
            description: 'returns 422 when HTTP code is missing',
            context: $context,
            payload: [
                'url' => 'https://example.com',
                'frequency' => 'daily',
                'user_email' => 'test@example.com',
            ],
            expectedErrorStructure: ['errors' => ['expected_http_code'], 'message']
        );
    });

    // Frequency
    describe('invalid frequency', function () use ($context): void {
        Scenario::forApiRoute()->invalid(
            description: 'returns 422 when frequency is invalid',
            context: $context,
            payload: [
                'url' => 'https://example.com',
                'expected_http_code' => 200,
                'frequency' => 'invalid-frequency',
                'user_email' => 'test@example.com',
            ],
            expectedErrorStructure: ['errors' => ['frequency'], 'message']
        );

        Scenario::forApiRoute()->invalid(
            description: 'returns 422 when frequency is missing',
            context: $context,
            payload: [
                'url' => 'https://example.com',
                'expected_http_code' => 200,
                'user_email' => 'test@example.com',
            ],
            expectedErrorStructure: ['errors' => ['frequency'], 'message']
        );
    });

    // Mail
    describe('invalid mail', function () use ($context): void {
        Scenario::forApiRoute()->invalid(
            description: 'returns 422 when mail is invalid',
            context: $context,
            payload: [
                'url' => 'https://example.com',
                'expected_http_code' => 200,
                'frequency' => 'daily',
                'user_email' => 'invalid-mail',
            ],
            expectedErrorStructure: ['errors' => ['user_email'], 'message']
        );

        Scenario::forApiRoute()->invalid(
            description: 'returns 422 when mail is missing',
            context: $context,
            payload: [
                'url' => 'https://example.com',
                'expected_http_code' => 200,
                'frequency' => 'daily',
            ],
            expectedErrorStructure: ['errors' => ['user_email'], 'message']
        );
    });

    // Combined
    describe('combined invalid fields', function () use ($context): void {
        Scenario::forApiRoute()->invalid(
            description: 'returns 422 when all fields are invalid',
            context: $context,
            payload: [
                'url' => 'invalid-url',
                'expected_http_code' => 'invalid-code',
                'frequency' => 'invalid-frequency',
                'user_email' => 'invalid-mail',
            ],
            expectedErrorStructure: ['errors' => ['url', 'expected_http_code', 'frequency', 'user_email'], 'message']
        );

        Scenario::forApiRoute()->invalid(
            description: 'returns 422 when all fields are missing',
            context: $context,
            expectedErrorStructure: ['errors' => ['url', 'expected_http_code', 'frequency', 'user_email'], 'message']
        );
    });
});
