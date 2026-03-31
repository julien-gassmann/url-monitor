<?php

use App\Actions\MonitorAccessToken\VerifyMonitorAccessTokenAction;
use App\Enums\InvalidReasonEnum;
use Carbon\Carbon;

use function Jgss\LaravelPestScenarios\databaseSetup;
use function Pest\Laravel\assertDatabaseHas;

beforeEach(function (): void {
    Carbon::setTestNow(Carbon::parse('2026-01-01 12:00:00'));
});

afterEach(function (): void {
    Carbon::setTestNow();
});

/**
 * ───────────────────────────────────────
 * Valid scenarios for Action VerifyMonitorAccessTokenAction
 * ───────────────────────────────────────
 */
describe('Actions - VerifyMonitorAccessTokenAction : success', function (): void {
    it('verifies valid monitor access token correctly', function (): void {
        // Arrange: Create and get monitor access token
        databaseSetup('create_token');
        $monitor = queryMonitor('monitor');
        $monitorAccessToken = $monitor->accessTokens->first();

        // Act: Call action's handle method
        /** @noinspection PhpUnhandledExceptionInspection */
        $result = app(VerifyMonitorAccessTokenAction::class)->handle($monitorAccessToken);

        // Assert: Result has expected content
        expect($result->isValid)->toBeTrue()
            ->and($result->reason)->toBeNull()
            ->and($result->monitor)->toEqual($monitor->unsetRelations());

        // Assert: MonitorAccessToken has been marked as used
        assertDatabaseHas('monitor_access_tokens', [
            'id' => $monitorAccessToken->id,
            'used_at' => '2026-01-01 12:00:00',
        ]);
    });
});

/**
 * ───────────────────────────────────────
 * Invalid scenarios for Action VerifyMonitorAccessTokenAction
 * ───────────────────────────────────────
 */
describe('Actions - VerifyMonitorAccessTokenAction : failure', function (): void {
    it('verifies used monitor access token correctly', function (): void {
        // Arrange: Create and get monitor access token
        databaseSetup('create_used_token');
        $monitor = queryMonitor('monitor');
        $monitorAccessToken = $monitor->accessTokens->first();

        // Act: Call action's handle method
        /** @noinspection PhpUnhandledExceptionInspection */
        $result = app(VerifyMonitorAccessTokenAction::class)->handle($monitorAccessToken);

        // Assert: Result has expected content
        expect($result->isValid)->toBeFalse()
            ->and($result->reason)->toBe(InvalidReasonEnum::AlreadyUsed)
            ->and($result->monitor)->toBeNull;

        // Assert: MonitorAccessToken has been marked as used
        assertDatabaseHas('monitor_access_tokens', [
            'id' => $monitorAccessToken->id,
            'used_at' => '2026-01-01 12:00:00',
        ]);
    });

    it('verifies expired monitor access token correctly', function (): void {
        // Arrange: Create and get monitor access token
        databaseSetup('create_expired_token');
        $monitor = queryMonitor('monitor');
        $monitorAccessToken = $monitor->accessTokens->first();

        // Act: Call action's handle method
        /** @noinspection PhpUnhandledExceptionInspection */
        $result = app(VerifyMonitorAccessTokenAction::class)->handle($monitorAccessToken);

        // Assert: Result has expected content
        expect($result->isValid)->toBeFalse()
            ->and($result->reason)->toBe(InvalidReasonEnum::Expired)
            ->and($result->monitor)->toBeNull;
    });
});
