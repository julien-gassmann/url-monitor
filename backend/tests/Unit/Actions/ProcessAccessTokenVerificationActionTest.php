<?php

use App\Actions\ProcessAccessTokenVerification;
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
 * Valid scenarios for Action ProcessAccessTokenVerification
 * ───────────────────────────────────────
 */
describe('Actions - ProcessAccessTokenVerification : success', function (): void {
    it('verifies valid access token', function (): void {
        // Arrange: Create and get monitor access token
        databaseSetup('create_many_tokens');
        $monitor = queryMonitor('monitor');
        $monitorAccessToken = $monitor->accessTokens->last();

        // Act: Call action's handle method
        /** @noinspection PhpUnhandledExceptionInspection */
        $result = app(ProcessAccessTokenVerification::class)->handle($monitorAccessToken);

        // Assert: Result has expected content
        expect($result->isValid)->toBeTrue()
            ->and($result->reason)->toBeNull()
            ->and($result->monitor)->toEqual($monitor->unsetRelation('accessTokens'))
            ->and($result->bearer->token)->not->toBeNull()
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
 * Invalid scenarios for Action ProcessAccessTokenVerification
 * ───────────────────────────────────────
 */
describe('Actions - ProcessAccessTokenVerification : failure', function (): void {
    it('verifies used access token', function (): void {
        // Arrange: Create and get monitor access token
        databaseSetup('create_used_token');
        $monitor = queryMonitor('monitor');
        $monitorAccessToken = $monitor->accessTokens->last();

        // Act: Call action's handle method
        /** @noinspection PhpUnhandledExceptionInspection */
        $result = app(ProcessAccessTokenVerification::class)->handle($monitorAccessToken);

        // Assert: Result has expected content
        expect($result->isValid)->toBeFalse()
            ->and($result->reason)->toBe(InvalidReasonEnum::AlreadyUsed)
            ->and($result->monitor)->toBeNull()
            ->and($result->bearer->token)->toBeNull()
            ->and($result->bearer->expiresAt)->toBeNull();

        // Assert: Bearer token has not been created
        assertDatabaseEmpty('personal_access_tokens');
    });

    it('verifies expired access token', function (): void {
        // Arrange: Create and get monitor access token
        databaseSetup('create_expired_token');
        $monitor = queryMonitor('monitor');
        $monitorAccessToken = $monitor->accessTokens->last();

        // Act: Call action's handle method
        /** @noinspection PhpUnhandledExceptionInspection */
        $result = app(ProcessAccessTokenVerification::class)->handle($monitorAccessToken);

        // Assert: Result has expected content
        expect($result->isValid)->toBeFalse()
            ->and($result->reason)->toBe(InvalidReasonEnum::Expired)
            ->and($result->monitor)->toBeNull()
            ->and($result->bearer->token)->toBeNull()
            ->and($result->bearer->expiresAt)->toBeNull();

        // Assert: Bearer token has not been created
        assertDatabaseEmpty('personal_access_tokens');
    });
});
