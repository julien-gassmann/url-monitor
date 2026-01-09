<?php

use App\Actions\User\CreateBearerTokenAction;
use App\Dto\VerifyAccessTokenResult;
use App\Enums\InvalidReasonEnum;
use Carbon\Carbon;

use function Jgss\LaravelPestScenarios\databaseSetup;
use function Pest\Laravel\assertDatabaseEmpty;
use function Pest\Laravel\assertDatabaseHas;

beforeEach(function (): void {
    Carbon::setTestNow(Carbon::parse('2026-01-01 12:00:00'));
});

afterEach(function (): void {
    Carbon::setTestNow();
});

/**
 * ───────────────────────────────────────
 * Valid scenarios for Action CreateBearerTokenAction
 * ───────────────────────────────────────
 */
describe('Actions - CreateBearerTokenAction : success', function (): void {
    it('creates bearer token when verify result is valid', function (): void {
        // Arrange: Create token and result
        databaseSetup('create_monitor');
        $monitor = queryMonitor('monitor');
        $result = new VerifyAccessTokenResult(
            isValid: true,
            reason: null,
            monitor: $monitor,
        );

        // Act: Call action's handle method
        /** @noinspection PhpUnhandledExceptionInspection */
        $result = app(CreateBearerTokenAction::class)->handle($result);

        // Assert: Result has expected bearer token
        expect($result->bearer->token)->not->toBeNull()
            ->and($result->bearer->expiresAt)->toEqual(Carbon::parse('2026-01-01 12:30:00'));

        // Assert: Bearer token has been created
        assertDatabaseHas('personal_access_tokens', [
            'name' => 'monitor-access',
            'abilities' => json_encode(['view-monitor:'.$monitor->id]),
            'expires_at' => '2026-01-01 12:30:00',
        ]);
    });
});

/**
 * ───────────────────────────────────────
 * Invalid scenarios for Action CreateBearerTokenAction
 * ───────────────────────────────────────
 */
describe('Actions - CreateBearerTokenAction : failure', function (): void {
    it('cancels bearer token creation when verify fails', function (): void {
        // Arrange: Create result
        $result = new VerifyAccessTokenResult(
            isValid: false,
            reason: InvalidReasonEnum::AlreadyUsed,
            monitor: null,
        );

        // Act: Call action's handle method
        /** @noinspection PhpUnhandledExceptionInspection */
        $result = app(CreateBearerTokenAction::class)->handle($result);

        // Assert: Result has empty bearer token
        expect($result->bearer->token)->toBeNull()
            ->and($result->bearer->expiresAt)->toBeNull();

        // Assert: Bearer token has not been created
        assertDatabaseEmpty('personal_access_tokens');
    });
});
