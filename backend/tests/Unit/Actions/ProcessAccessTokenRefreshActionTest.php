<?php

use App\Actions\ProcessAccessTokenRefresh;
use App\Events\MonitorAccessTokenCreatedEvent;
use App\Models\MonitorAccessToken;
use Illuminate\Support\Facades\Event;

use function Jgss\LaravelPestScenarios\databaseSetup;

/**
 * ───────────────────────────────────────
 * Valid scenarios for Action ProcessAccessTokenRefresh
 * ───────────────────────────────────────
 */
describe('Actions - ProcessAccessTokenRefresh : success', function (): void {
    it('invalidate former access tokens and create new one', function (): void {
        // Arrange: Create and get monitor access token
        databaseSetup('create_many_tokens');
        $monitor = queryMonitor('monitor');
        $monitorAccessToken = $monitor->accessTokens->last();

        // Act: Call action's handle method
        Event::fake();
        /** @noinspection PhpUnhandledExceptionInspection */
        app(ProcessAccessTokenRefresh::class)->handle($monitorAccessToken);

        // Assert: All tokens are marked as used
        $usedTokens = MonitorAccessToken::whereNotNull('refreshed_at');
        $activeToken = MonitorAccessToken::whereNull('refreshed_at');

        expect($usedTokens->count())->toBe(5)
            ->and($activeToken->count())->toBe(1);

        // Assert: Monitor access token creation event has been dispatched
        Event::assertDispatched(
            MonitorAccessTokenCreatedEvent::class,
            fn (MonitorAccessTokenCreatedEvent $event): bool => $event->monitor->is($monitor)
                && $event->token !== ''
        );
    });
});
