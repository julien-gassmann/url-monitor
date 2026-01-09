<?php

use App\Actions\Monitor\ScheduleNextMonitorCheckAction;
use App\Enums\FrequencyEnum;
use Carbon\Carbon;

use function Jgss\LaravelPestScenarios\databaseSetup;

/**
 * ───────────────────────────────────────
 * Valid scenarios for Action ScheduleNextMonitorCheckAction
 * ───────────────────────────────────────
 */
describe('Actions - ScheduleNextMonitorCheckAction : success', function (): void {
    beforeEach(function (): void {
        Carbon::setTestNow(Carbon::parse('2026-01-01 12:00:00'));
    });

    afterEach(function (): void {
        Carbon::setTestNow(); // reset
    });

    it('schedules next check in normal mode', function (FrequencyEnum $frequency, string $expectedDate): void {
        // Arrange: Create and get monitor
        databaseSetup('create_monitor');
        $monitor = queryMonitor('monitor');
        $monitor->update(['frequency' => $frequency]);

        // Act: Call action's handle method
        /** @noinspection PhpUnhandledExceptionInspection */
        app(ScheduleNextMonitorCheckAction::class)->handle($monitor);

        // Assert: Monitor is correctly updated
        $monitor->refresh();
        expect($monitor->next_check_at->toDateTimeString())
            ->toBe($expectedDate);

    })->with([
        'daily' => [
            FrequencyEnum::DAILY,
            '2026-01-02 12:00:00',
        ],
        'weekly' => [
            FrequencyEnum::WEEKLY,
            '2026-01-08 12:00:00',
        ],
        'monthly' => [
            FrequencyEnum::MONTHLY,
            '2026-02-01 12:00:00',
        ],
    ]);

    it('schedules next check in time traveller mode', function (FrequencyEnum $frequency, string $expectedDate): void {
        // Arrange: Enable time traveller mode
        config()->set('app.time_traveller_mode_enabled', true);

        // Arrange: Create and get monitor
        databaseSetup('create_monitor');
        $monitor = queryMonitor('monitor');
        $monitor->update(['frequency' => $frequency]);

        // Act: Call action's handle method
        /** @noinspection PhpUnhandledExceptionInspection */
        app(ScheduleNextMonitorCheckAction::class)->handle($monitor);

        // Assert: Monitor is correctly updated
        $monitor->refresh();
        expect($monitor->next_check_at->toDateTimeString())
            ->toBe($expectedDate);

    })->with([
        'daily' => [
            FrequencyEnum::DAILY,
            '2026-01-01 12:01:00',
        ],
        'weekly' => [
            FrequencyEnum::WEEKLY,
            '2026-01-01 12:05:00',
        ],
        'monthly' => [
            FrequencyEnum::MONTHLY,
            '2026-01-01 12:10:00',
        ],
    ]);
});
