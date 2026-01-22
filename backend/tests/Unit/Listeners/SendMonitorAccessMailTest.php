<?php

use App\Events\MonitorAccessTokenCreatedEvent;
use App\Listeners\SendMonitorAccessMail;
use App\Mail\MonitorAccessMail;
use Illuminate\Support\Facades\Mail;

use function Jgss\LaravelPestScenarios\databaseSetup;

/**
 * ───────────────────────────────────────
 * Valid scenarios for Listener SendMonitorAccessMail
 * ───────────────────────────────────────
 */
describe('Listeners - SendMonitorAccessMail : success', function (): void {
    describe('mail sending is enabled', function (): void {
        it('sends the monitor access mail', function (): void {
            // Arrange: Create monitor, user and token
            databaseSetup('create_monitor');
            $monitor = queryMonitor('monitor');
            $token = 'plain-access-token';

            // Arrange: Create event and listener
            $event = new MonitorAccessTokenCreatedEvent($monitor, $token);
            $listener = new SendMonitorAccessMail;

            // Act: Handle event
            $listener->handle($event);

            // Assert: Mail is sent to expected user with expected content
            Mail::assertSent(
                MonitorAccessMail::class,
                fn (MonitorAccessMail $mail): bool => $mail->hasTo($monitor->user->email)
                    && $mail->monitor->is($monitor)
                    && $mail->token === $token);
        });
    });

    describe('mail sending is disabled', function (): void {
        it('does not send mail', function (): void {
            // Arrange: Set config to disable mail sending
            config()->set('mail.sending_enabled', false);

            // Arrange: Create monitor, user and token
            databaseSetup('create_monitor');
            $monitor = queryMonitor('monitor');

            // Arrange: Create event and listener
            $event = new MonitorAccessTokenCreatedEvent($monitor, 'token');
            $listener = new SendMonitorAccessMail;

            // Act: Handle event
            $listener->handle($event);

            // Assert: Mail has not been sent
            Mail::assertNothingSent();
        });
    });
});
