<?php

use App\Actions\MonitorAccessToken\CreateMonitorAccessTokenAction;
use App\Events\MonitorAccessTokenCreatedEvent;
use Carbon\Carbon;
use Illuminate\Support\Facades\Event;

use function Jgss\LaravelPestScenarios\databaseSetup;
use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\assertDatabaseHas;

/**
 * ───────────────────────────────────────
 * Valid scenarios for Action CreateMonitorAccessTokenAction
 * ───────────────────────────────────────
 */
describe('Actions - CreateMonitorAccessTokenAction : success', function (): void {
    beforeEach(function (): void {
        Carbon::setTestNow(Carbon::parse('2026-01-01 12:00:00'));
    });

    afterEach(function (): void {
        Carbon::setTestNow();
    });

    it('creates a monitor access token and dispatches event', function (): void {
        // Arrange: Create and get monitor
        databaseSetup('create_monitor');
        $monitor = queryMonitor('monitor');

        // Act: Call action's handle method
        Event::fake();
        /** @noinspection PhpUnhandledExceptionInspection */
        app(CreateMonitorAccessTokenAction::class)->handle($monitor);

        // Assert: MonitorAccessToken has been created
        assertDatabaseCount('monitor_access_tokens', 1);
        assertDatabaseHas('monitor_access_tokens', [
            'monitor_id' => $monitor->id,
            'expires_at' => '2026-01-01 12:05:00',
        ]);

        // Assert: Event has been dispatched
        Event::assertDispatched(
            MonitorAccessTokenCreatedEvent::class,
            fn (MonitorAccessTokenCreatedEvent $event): bool => $event->monitor->is($monitor)
                && $event->token !== ''
        );
    });
});
