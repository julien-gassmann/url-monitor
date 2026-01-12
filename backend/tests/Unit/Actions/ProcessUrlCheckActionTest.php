<?php

use App\Actions\ProcessUrlCheckAction;
use App\Enums\StatusEnum;
use App\Events\MonitorAccessTokenCreatedEvent;
use Carbon\Carbon;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Http;

use function Jgss\LaravelPestScenarios\databaseSetup;
use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\assertDatabaseHas;

beforeEach(function (): void {
    config()->set('app.time_traveller_mode_enabled', false);
    Carbon::setTestNow(Carbon::parse('2026-01-01 12:00:00'));
});

afterEach(function (): void {
    Carbon::setTestNow();
});

/**
 * ───────────────────────────────────────
 * Valid scenarios for Action ProcessUrlCheckAction
 * ───────────────────────────────────────
 */
describe('Actions - ProcessUrlCheckAction : success', function (): void {
    it('verifies valid access token', function (?int $httpCode, string $expectedStatus): void {
        // Arrange: Create and get monitor access token
        databaseSetup('create_monitor');
        $monitor = queryMonitor('monitor');

        // Arrange: Fake HTTP client
        $fake = is_null($httpCode)
            ? fn () => throw new Exception('Network error')
            : Http::response('', $httpCode);

        Http::fake([$monitor->url => $fake]);

        // Act: Call action's handle method
        Event::fake();
        /** @noinspection PhpUnhandledExceptionInspection */
        app(ProcessUrlCheckAction::class)->handle($monitor);

        // CreateMonitorCheckAction
        // Assert: Database has new user and monitor
        assertDatabaseHas('monitor_checks', [
            'monitor_id' => $monitor->id,
            'http_code' => $httpCode,
            'status' => $expectedStatus,
        ]);

        // ScheduleNextMonitorCheckAction
        // Assert: Monitor is correctly updated
        $monitor->refresh();
        expect($monitor->next_check_at->toDateTimeString())
            ->toBe('2026-01-02 12:00:00');

        // CreateMonitorAccessTokenAction
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
    })->with([
        '200' => [200, StatusEnum::UP->value],
        '404' => [404, StatusEnum::DOWN->value],
        'N/A' => [null, StatusEnum::UNREACHABLE->value],
    ]);
});
