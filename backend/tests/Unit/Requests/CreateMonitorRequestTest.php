<?php

use App\Http\Requests\CreateMonitorRequest;
use Jgss\LaravelPestScenarios\Context;
use Jgss\LaravelPestScenarios\Scenario;

$context = Context::forFormRequest()->with(
    formRequestClass: CreateMonitorRequest::class,
    routeName: 'monitors.create',
);

/**
 * ───────────────────────────────────────
 * Valid scenarios for CreateMonitorRequest (route: monitors.create)
 * ───────────────────────────────────────
 */
describe('FormRequests - CreateMonitorRequest (monitors.create) : success', function () use ($context): void {
    Scenario::forFormRequest()->valid(
        description: 'passes with valid payload',
        context: $context,
        // --- Payload --------------------------------------------------------------
        payload: [
            'url' => 'https://example.com',
            'expected_http_code' => 200,
            'frequency' => 'daily',
            'user_email' => 'test@example.com',
        ],
    );
});

/**
 * ───────────────────────────────────────
 * Invalid scenarios for FormRequest CreateMonitorRequest (route: monitors.create)
 * ───────────────────────────────────────
 */
describe('FormRequests - CreateMonitorRequest (monitors.create) : failure', function () use ($context): void {
    describe('URL', function () use ($context): void {
        Scenario::forFormRequest()->invalid(
            description: 'fails when missing',
            context: $context,
            // --- Payload --------------------------------------------------------------
            payload: [
                'expected_http_code' => 200,
                'frequency' => 'daily',
                'user_email' => 'test@example.com',
            ],
            // --- Expected errors ------------------------------------------------------
            expectedValidationErrors: ['url' => ['required']],
        );

        Scenario::forFormRequest()->invalid(
            description: 'fails when not valid',
            context: $context,
            // --- Payload --------------------------------------------------------------
            payload: [
                'url' => 'not-valid-url',
                'expected_http_code' => 200,
                'frequency' => 'daily',
                'user_email' => 'test@example.com',
            ],
            // --- Expected errors ------------------------------------------------------
            expectedValidationErrors: ['url' => ['url']],
        );

        Scenario::forFormRequest()->invalid(
            description: 'fails when too short',
            context: $context,
            // --- Payload --------------------------------------------------------------
            payload: [
                'url' => 'http://a.b',
                'expected_http_code' => 200,
                'frequency' => 'daily',
                'user_email' => 'test@example.com',
            ],
            // --- Expected errors ------------------------------------------------------
            expectedValidationErrors: ['url' => ['min.string|min=11']],
        );
    });

    describe('status code', function () use ($context): void {
        Scenario::forFormRequest()->invalid(
            description: 'fails when missing',
            context: $context,
            // --- Payload --------------------------------------------------------------
            payload: [
                'url' => 'https://example.com',
                'frequency' => 'daily',
                'user_email' => 'test@example.com',
            ],
            // --- Expected errors ------------------------------------------------------
            expectedValidationErrors: ['expected_http_code' => ['required']],
        );

        Scenario::forFormRequest()->invalid(
            description: 'fails when not valid',
            context: $context,
            // --- Payload --------------------------------------------------------------
            payload: [
                'url' => 'https://example.com',
                'expected_http_code' => 999,
                'frequency' => 'daily',
                'user_email' => 'test@example.com',
            ],
            // --- Expected errors ------------------------------------------------------
            expectedValidationErrors: ['expected_http_code' => ['enum']],
        );
    });

    describe('frequency', function () use ($context): void {
        Scenario::forFormRequest()->invalid(
            description: 'fails when missing',
            context: $context,
            // --- Payload --------------------------------------------------------------
            payload: [
                'url' => 'https://example.com',
                'expected_http_code' => 200,
                'user_email' => 'test@example.com',
            ],
            // --- Expected errors ------------------------------------------------------
            expectedValidationErrors: ['frequency' => ['required']],
        );

        Scenario::forFormRequest()->invalid(
            description: 'fails when not valid',
            context: $context,
            // --- Payload --------------------------------------------------------------
            payload: [
                'url' => 'https://example.com',
                'expected_http_code' => 200,
                'frequency' => 'invalid',
                'user_email' => 'test@example.com',
            ],
            // --- Expected errors ------------------------------------------------------
            expectedValidationErrors: ['frequency' => ['enum']],
        );
    });

    describe('user email', function () use ($context): void {
        Scenario::forFormRequest()->invalid(
            description: 'fails when missing',
            context: $context,
            // --- Payload --------------------------------------------------------------
            payload: [
                'url' => 'https://example.com',
                'expected_http_code' => 200,
                'frequency' => 'daily',
            ],
            // --- Expected errors ------------------------------------------------------
            expectedValidationErrors: ['user_email' => ['required']],
        );

        Scenario::forFormRequest()->invalid(
            description: 'fails when not valid',
            context: $context,
            // --- Payload --------------------------------------------------------------
            payload: [
                'url' => 'https://example.com',
                'expected_http_code' => 200,
                'frequency' => 'daily',
                'user_email' => 'not-valid-email',
            ],
            // --- Expected errors ------------------------------------------------------
            expectedValidationErrors: ['user_email' => ['email']],
        );

        Scenario::forFormRequest()->invalid(
            description: 'fails when too short',
            context: $context,
            // --- Payload --------------------------------------------------------------
            payload: [
                'url' => 'https://example.com',
                'expected_http_code' => 200,
                'frequency' => 'daily',
                'user_email' => 'a@b.c',
            ],
            // --- Expected errors ------------------------------------------------------
            expectedValidationErrors: ['user_email' => ['min.string|min=6']],
        );
    });
});
