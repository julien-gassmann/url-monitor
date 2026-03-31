<?php

use App\Actions\ProcessUrlCheckAction;
use App\Jobs\CheckMonitorJob;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

use function Jgss\LaravelPestScenarios\databaseSetup;

/**
 * ───────────────────────────────────────
 * Valid scenarios for Job CheckMonitorJob
 * ───────────────────────────────────────
 */
describe('Jobs - CheckMonitorJob : success', function (): void {
    it('processes URL check', function (): void {
        // Arrange: Create monitor
        databaseSetup('create_monitor');
        $monitor = queryMonitor('monitor');

        // Arrange: Mock HTTP facade
        Http::fake([$monitor->url => Http::response()]);

        // Act: Call handle method
        $job = new CheckMonitorJob($monitor);
        $job->handle(app(ProcessUrlCheckAction::class));
        $checks = $monitor->fresh()->checks ?? collect();

        // Assert: Check has been run
        expect($checks->count())->toBe(1)
            ->and($checks->first()?->http_code->value)->toBe(200);
    });
});

/**
 * ───────────────────────────────────────
 * Invalid scenarios for Job CheckMonitorJob
 * ───────────────────────────────────────
 */
describe('Jobs - CheckMonitorJob : failure', function (): void {
    it('logs an error when the job fails', function (): void {
        // Arrange: Create monitor and job
        databaseSetup('create_monitor');
        $monitor = queryMonitor('monitor');
        $job = new CheckMonitorJob($monitor);

        // Act: Call failed method
        $spy = Log::spy();
        $exception = new RuntimeException('error message');
        $job->failed($exception);

        // Assert: Failure is correctly logged
        $spy->shouldHaveReceived('error')
            ->once()
            ->withArgs(
                fn (string $message, array $context): bool => str_contains($message, 'Check failed for monitor')
                    && $context['monitor_id'] === $monitor->id
                    && $context['error'] === 'error message'
            );
    });
});
