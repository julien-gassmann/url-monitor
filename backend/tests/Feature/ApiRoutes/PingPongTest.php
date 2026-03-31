<?php

use App\Services\RandomPing;
use Jgss\LaravelPestScenarios\Context;
use Jgss\LaravelPestScenarios\Scenario;
use Mockery\MockInterface;

use function Jgss\LaravelPestScenarios\makeMock;

$context = Context::forApiRoute()->with(
    routeName: 'ping.pong',
    mocks: makeMock(
        RandomPing::class,
        fn (MockInterface $mock) => $mock
            ->shouldReceive('pong')->andReturn(200),
    )
);

/**
 * ───────────────────────────────────────
 * Valid scenarios for route: ping.pong
 * ───────────────────────────────────────
 */
describe('GET api/ping : success', function () use ($context): void {
    Scenario::forApiRoute()->valid(
        description: 'returns status 200 when UP',
        context: $context,
        // --- Expected response ----------------------------------------------------
        expectedResponse: fn () => response()->json(['data' => 'pong']),
    );

    Scenario::forApiRoute()->valid(
        description: 'returns status 404 when DOWN',
        context: $context->withMocks(makeMock(
            RandomPing::class,
            fn (MockInterface $mock) => $mock
                ->shouldReceive('pong')->andReturn(404),
        )),
        // --- Expected status ------------------------------------------------------
        expectedStatusCode: 404,
        // --- Expected response ----------------------------------------------------
        expectedResponse: fn () => response()->json(['data' => 'pong']),
    );

    Scenario::forApiRoute()->valid(
        description: 'returns status 418 when UNREACHABLE',
        context: $context->withMocks(makeMock(
            RandomPing::class,
            fn (MockInterface $mock) => $mock
                ->shouldReceive('pong')->andReturn(418),
        )),
        // --- Expected status ------------------------------------------------------
        expectedStatusCode: 418,
        // --- Expected response ----------------------------------------------------
        expectedResponse: fn () => response()->json(['data' => 'pong']),
    );
});
