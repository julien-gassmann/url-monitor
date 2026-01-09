<?php

/**
 * ───────────────────────────────────────
 * Valid scenarios for Action CreateMonitorCheckAction
 * ───────────────────────────────────────
 */

use App\Actions\Monitor\CreateMonitorCheckAction;
use App\Enums\StatusEnum;

use function Jgss\LaravelPestScenarios\databaseSetup;
use function Pest\Laravel\assertDatabaseHas;

/**
 * ───────────────────────────────────────
 * Valid scenarios for Action CreateMonitorCheckAction
 * ───────────────────────────────────────
 */
describe('Actions - CreateMonitorCheckAction : success', function (): void {
    it('creates monitor checks correctly', function (?int $httpCode, string $expectedStatus): void {
        // Arrange: Create monitor expecting HTTP code 200
        databaseSetup('create_monitor');
        $monitor = queryMonitor('monitor');

        // Act: Call action's handle method
        /** @noinspection PhpUnhandledExceptionInspection */
        app(CreateMonitorCheckAction::class)->handle($monitor, $httpCode);

        // Assert: Database has new user and monitor
        assertDatabaseHas('monitor_checks', [
            'monitor_id' => $monitor->id,
            'http_code' => $httpCode,
            'status' => $expectedStatus,
        ]);
    })->with([
        '200' => [200, StatusEnum::UP->value],
        '404' => [404, StatusEnum::DOWN->value],
        'N/A' => [null, StatusEnum::UNREACHABLE->value],
    ]);
});
