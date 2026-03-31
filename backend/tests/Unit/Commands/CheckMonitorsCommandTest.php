<?php

use App\Jobs\CheckMonitorJob;
use App\Models\Monitor;
use Illuminate\Support\Facades\Queue;
use Jgss\LaravelPestScenarios\Context;

use function Jgss\LaravelPestScenarios\databaseSetup;
use function Pest\Laravel\artisan;

$context = Context::forCommand()->with(
    command: 'monitors:checks',
    databaseSetup: 'create_many_monitors'
);

/**
 * ───────────────────────────────────────
 * Valid scenarios for command: monitors:checks
 * ───────────────────────────────────────
 */
describe('Commands - artisan:command : success', function (): void {
    it('dispatches all monitors due for checks', function (): void {
        // Arrange: Create monitors and retrieve the ones dus for check
        databaseSetup('create_many_monitors');
        $monitors = Monitor::dueForCheck()->get();

        // Assert: 10 monitors are due for check
        expect($monitors)->toHaveCount(10);

        // Act: Run artisan command
        /**  @phpstan-ignore-next-line  */
        artisan('monitors:checks')->assertSuccessful();

        // Assert: 10 jobs have been dispatched
        Queue::assertPushed(CheckMonitorJob::class, 10);

        // Assert: Each job match one of the monitors
        $pushedMonitorIds = collect(Queue::pushed(CheckMonitorJob::class))
            ->pluck('monitor.id');

        expect($pushedMonitorIds->sort()->values())
            ->toEqual($monitors->pluck('id')->sort()->values());
    });
});
