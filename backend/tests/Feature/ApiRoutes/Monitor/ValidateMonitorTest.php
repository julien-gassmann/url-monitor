<?php

use Jgss\LaravelPestScenarios\Context;
use Jgss\LaravelPestScenarios\Scenario;

$context = Context::forApiRoute()->with(
    routeName: 'monitors.validate',
);

/**
 * ───────────────────────────────────────
 * Valid scenarios for route: monitors.validate
 * ───────────────────────────────────────
 */
describe('POST api/monitors/validate : success', function () use ($context): void {
    Scenario::forApiRoute()->valid(
        description: 'returns 200 when validate URL',
        context: $context,
        payload: ['url' => 'https://example.com'],
        expectedStructure: 'none',
        expectedResponse: fn () => response()->json(),
    );

    Scenario::forApiRoute()->valid(
        description: 'returns 200 when validate HTTP code',
        context: $context,
        payload: ['expected_http_code' => 200],
        expectedStructure: 'none',
        expectedResponse: fn () => response()->json(),
    );

    Scenario::forApiRoute()->valid(
        description: 'returns 200 when validate frequency',
        context: $context,
        payload: ['frequency' => 'daily'],
        expectedStructure: 'none',
        expectedResponse: fn () => response()->json(),
    );

    Scenario::forApiRoute()->valid(
        description: 'returns 200 when validate mail',
        context: $context,
        payload: ['user_email' => 'test@example.com'],
        expectedStructure: 'none',
        expectedResponse: fn () => response()->json(),
    );

    Scenario::forApiRoute()->valid(
        description: 'returns 200 when validate multiple fields',
        context: $context,
        payload: [
            'url' => 'https://example.com',
            'expected_http_code' => 200,
            'frequency' => 'daily',
            'user_email' => 'test@example.com',
        ],
        expectedStructure: 'none',
        expectedResponse: fn () => response()->json(),
    );
});

/**
 * ───────────────────────────────────────
 * Invalid scenarios for route: monitors.validate
 * ───────────────────────────────────────
 */
describe('POST api/monitors/validate : failure', function () use ($context): void {
    Scenario::forApiRoute()->invalid(
        description: 'returns 422 when URL is invalid',
        context: $context,
        payload: ['url' => 'invalid-url'],
        expectedErrorStructure: ['errors' => ['url'], 'message']
    );

    Scenario::forApiRoute()->invalid(
        description: 'returns 422 when HTTP code is string',
        context: $context,
        payload: ['expected_http_code' => 'not-valid'],
        expectedErrorStructure: ['errors' => ['expected_http_code'], 'message']
    );

    Scenario::forApiRoute()->invalid(
        description: 'returns 422 when frequency is invalid',
        context: $context,
        payload: ['frequency' => 'invalid-frequency'],
        expectedErrorStructure: ['errors' => ['frequency'], 'message']
    );

    Scenario::forApiRoute()->invalid(
        description: 'returns 422 when mail is invalid',
        context: $context,
        payload: ['user_email' => 'invalid-mail'],
        expectedErrorStructure: ['errors' => ['user_email'], 'message']
    );

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
});
