<?php

namespace App\Mail;

use App\Models\Monitor;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class MonitorCheckedMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public Monitor $monitor
    ) {}

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
            view: 'mail.monitor_checked',
        );
    }
}
