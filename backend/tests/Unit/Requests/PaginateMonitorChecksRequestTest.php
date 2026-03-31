<?php

use App\Http\Requests\PaginateMonitorChecksRequest;
use App\Models\MonitorCheck;
use Jgss\LaravelPestScenarios\Context;
use Jgss\LaravelPestScenarios\Scenario;

$context = Context::forFormRequest()->with(
    formRequestClass: PaginateMonitorChecksRequest::class,
    routeName: 'monitors.checks.paginate',
    routeParameters: ['monitor' => getQueryUuid('monitor')],
    actingAs: 'sanctum',
    databaseSetup: 'create_checks',
);

/**
 * ───────────────────────────────────────
 * Valid scenarios for PaginateMonitorChecksRequest
 * ───────────────────────────────────────
 */
describe('FormRequests - PaginateMonitorChecksRequest : success', function () use ($context): void {
    Scenario::forFormRequest()->valid(
        description: 'passes with valid token and pagination',
        context: $context,
        // --- Payload --------------------------------------------------------------
        payload: [
            'page' => 1,
            'per_page' => 10,
        ],
    );

    Scenario::forFormRequest()->valid(
        description: 'passes with all results',
        context: $context,
        // --- Payload --------------------------------------------------------------
        payload: [
            'page' => 1,
            'per_page' => -1,
        ],
    );

    foreach (MonitorCheck::allowedSorts() as $sort) {
        Scenario::forFormRequest()->valid(
            description: "passes with sorting : '$sort'",
            context: $context,
            // --- Payload --------------------------------------------------------------
            payload: [
                'page' => 1,
                'per_page' => 10,
                'sort' => $sort,
            ],
        );
    }
});

/**
 * ───────────────────────────────────────
 * Invalid scenarios for FormRequest PaginateMonitorChecksRequest
 * ───────────────────────────────────────
 */
describe('FormRequests - PaginateMonitorChecksRequest : failure', function () use ($context): void {
    describe('invalid parameters', function () use ($context): void {
        describe('page', function () use ($context): void {
            Scenario::forFormRequest()->invalid(
                description: 'fails when missing',
                context: $context,
                // --- Payload --------------------------------------------------------------
                payload: ['per_page' => 10],
                // --- Expected errors ------------------------------------------------------
                expectedValidationErrors: ['page' => ['required']],
            );

            Scenario::forFormRequest()->invalid(
                description: 'fails when not an integer',
                context: $context,
                // --- Payload --------------------------------------------------------------
                payload: [
                    'page' => 'not an integer',
                    'per_page' => 10,
                ],
                // --- Expected errors ------------------------------------------------------
                expectedValidationErrors: ['page' => ['integer', 'gte.numeric|value=1']],
            );

            Scenario::forFormRequest()->invalid(
                description: 'fails when lower than 1',
                context: $context,
                // --- Payload --------------------------------------------------------------
                payload: [
                    'page' => -2,
                    'per_page' => 10,
                ],
                // --- Expected errors ------------------------------------------------------
                expectedValidationErrors: ['page' => ['gte.numeric|value=1']],
            );
        });

        describe('per_page', function () use ($context): void {
            Scenario::forFormRequest()->invalid(
                description: 'fails when missing',
                context: $context,
                // --- Payload --------------------------------------------------------------
                payload: ['page' => 1],
                // --- Expected errors ------------------------------------------------------
                expectedValidationErrors: ['per_page' => ['required']],
            );

            Scenario::forFormRequest()->invalid(
                description: 'fails when not an integer',
                context: $context,
                // --- Payload --------------------------------------------------------------
                payload: [
                    'page' => 1,
                    'per_page' => 'not an integer',
                ],
                // --- Expected errors ------------------------------------------------------
                expectedValidationErrors: ['per_page' => ['integer', 'enum']],
            );

            Scenario::forFormRequest()->invalid(
                description: 'fails when is not in enum',
                context: $context,
                // --- Payload --------------------------------------------------------------
                payload: [
                    'page' => 1,
                    'per_page' => 200,
                ],
                // --- Expected errors ------------------------------------------------------
                expectedValidationErrors: ['per_page' => ['enum']],
            );
        });

        describe('sort', function () use ($context): void {
            Scenario::forFormRequest()->invalid(
                description: 'fails when not a string',
                context: $context,
                // --- Payload --------------------------------------------------------------
                payload: [
                    'page' => 1,
                    'per_page' => 10,
                    'sort' => 10,
                ],
                // --- Expected errors ------------------------------------------------------
                expectedValidationErrors: ['sort' => ['string', 'in']],
            );

            Scenario::forFormRequest()->invalid(
                description: 'fails when not in allowed attributed',
                context: $context,
                // --- Payload --------------------------------------------------------------
                payload: [
                    'page' => 1,
                    'per_page' => 10,
                    'sort' => 'not-allowed',
                ],
                // --- Expected errors ------------------------------------------------------
                expectedValidationErrors: ['sort' => ['in']],
            );
        });
    });

    describe('invalid authorizations', function () use ($context): void {
        Scenario::forFormRequest()->invalid(
            description: 'fails acting as guest',
            context: $context->withActingAs('guest'),
            // --- Authorize ------------------------------------------------------------
            shouldAuthorize: false
        );

        Scenario::forFormRequest()->invalid(
            description: 'fails acting as other user',
            context: $context->withDatabaseSetup(['create_checks', 'create_other_checks'])
                ->withActingAs('other'),
            // --- Authorize ------------------------------------------------------------
            shouldAuthorize: false
        );
    });
});
