<?php

use App\Actions\MonitorAccessToken\InvalidateMonitorAccessTokensAction;

use function Jgss\LaravelPestScenarios\databaseSetup;
use function Pest\Laravel\assertDatabaseMissing;

/**
 * ───────────────────────────────────────
 * Valid scenarios for Action InvalidateMonitorAccessTokensAction
 * ───────────────────────────────────────
 */
describe('Actions - InvalidateMonitorAccessTokensAction : success', function (): void {
    it('marks all unused tokens as used on verify', function (): void {
        // Arrange: Create monitor access tokens
        databaseSetup('create_many_tokens');
        $monitor = queryMonitor('monitor');

        // Act: Call action's handle method
        app(InvalidateMonitorAccessTokensAction::class)->onVerify($monitor);

        // Assert: All tokens are marked as used
        assertDatabaseMissing('monitor_access_tokens', [
            'monitor_id' => $monitor->id,
            'used_at' => null,
        ]);
    });

    it('marks unused tokens as used and refreshed on refresh', function (): void {
        // Arrange: Create monitor access tokens
        databaseSetup('create_many_tokens');
        $monitor = queryMonitor('monitor');

        // Act: Call action's handle method
        app(InvalidateMonitorAccessTokensAction::class)->onRefresh($monitor);

        // Assert: All tokens are marked as used
        assertDatabaseMissing('monitor_access_tokens', [
            'monitor_id' => $monitor->id,
            'used_at' => null,
            'refreshed_at' => null,
        ]);
    });
});
