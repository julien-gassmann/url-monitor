<?php

declare(strict_types=1);

namespace App\Mail;

use App\Models\Monitor;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class MonitorAccessMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $verifyUrl;

    public string $refreshUrl;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public Monitor $monitor,
        public string $token,
    ) {
        /** @var string $baseUrl */
        $baseUrl = config('app.front_base_url');

        $this->verifyUrl = "$baseUrl/verify/$token";
        $this->refreshUrl = "$baseUrl/refresh/$token";
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        /** @var string $from */
        $from = config('mail.from.address');

        return new Envelope(
            from: $from,
            subject: 'Nouvelle surveillance disponible',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mails.monitor_checked',
        );
    }
}
