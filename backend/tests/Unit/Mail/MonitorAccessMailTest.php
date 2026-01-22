<?php

use App\Mail\MonitorAccessMail;
use Illuminate\Mail\Mailables\Address;

use function Jgss\LaravelPestScenarios\databaseSetup;

/**
 * ───────────────────────────────────────
 * Valid scenarios for Mail MonitorAccessMail
 * ───────────────────────────────────────
 */
describe('Mails - MonitorAccessMail : success', function (): void {
    it('builds verify and refresh urls from token and front base url', function (): void {
        // Arrange: Set config for front_base_url
        config()->set('app.front_base_url', 'https://front.test');

        // Arrange: Create monitor and token
        databaseSetup('create_monitor');
        $monitor = queryMonitor('monitor');
        $token = 'plain-access-token';

        // Act: Create mail class
        $mail = new MonitorAccessMail($monitor, $token);

        // Assert: Verify and refresh URL are correct
        expect($mail->verifyUrl)->toBe('https://front.test/verify/plain-access-token')
            ->and($mail->refreshUrl)->toBe('https://front.test/refresh/plain-access-token');
    });

    it('defines the correct mail envelope', function (): void {
        // Arrange: Set config for mail from address
        config()->set('mail.from.address', 'noreply@test.com');

        // Arrange: Create monitor
        databaseSetup('create_monitor');
        $monitor = queryMonitor('monitor');

        // Act: Create mail and get envelope
        $mail = new MonitorAccessMail($monitor, 'plain-access-token');
        $envelope = $mail->envelope();

        // Assert: Mail subject and from address is correct
        /** @var Address $from */
        $from = $envelope->from;
        $subject = $envelope->subject;
        expect($subject)->toBe('Nouvelle surveillance disponible')
            ->and($from->address)->toBe('noreply@test.com');
    });

    it('uses the expected view', function (): void {
        // Arrange: Create monitor
        databaseSetup('create_monitor');
        $monitor = queryMonitor('monitor');

        // Act: Create mail and get content
        $mail = new MonitorAccessMail($monitor, 'plain-access-token');
        $content = $mail->content();

        // Assert: View is correct
        expect($content->view)->toBe('mails.monitor_checked');
    });
});
