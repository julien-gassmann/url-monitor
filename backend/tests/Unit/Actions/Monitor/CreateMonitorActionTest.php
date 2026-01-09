<?php

use App\Actions\Monitor\CreateMonitorAction;
use App\Http\Requests\CreateMonitorRequest;
use App\Jobs\CheckMonitorJob;
use App\Models\User;
use Illuminate\Support\Facades\Queue;

use function Pest\Laravel\assertDatabaseHas;

/**
 * ───────────────────────────────────────
 * Valid scenarios for Action CreateMonitorAction
 * ───────────────────────────────────────
 */
describe('Actions - CreateMonitorAction : success', function (): void {
    it('creates user and monitor and dispatches job', function (): void {
        // Arrange: Create payload
        $request = CreateMonitorRequest::create('', parameters: [
            'url' => 'https://example.com',
            'expected_http_code' => 200,
            'frequency' => 'daily',
            'user_email' => 'test@example.com',
        ]);
        $request->setContainer(app());
        $request->validateResolved();

        // Act: Call action's handle method
        /** @noinspection PhpUnhandledExceptionInspection */
        app(CreateMonitorAction::class)->handle($request);

        // Assert: Database has new user and monitor
        assertDatabaseHas('users', [
            'email' => 'test@example.com',
        ]);
        assertDatabaseHas('monitors', [
            'url' => 'https://example.com',
            'expected_http_code' => 200,
            'frequency' => 'daily',
            'user_id' => User::firstOrFail()->id,
        ]);

        // Asser: Job is dispatch
        Queue::assertPushed(CheckMonitorJob::class, 1);
    });
});
