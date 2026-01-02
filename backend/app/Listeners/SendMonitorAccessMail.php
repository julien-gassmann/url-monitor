<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\MonitorAccessTokenCreatedEvent;
use App\Mail\MonitorAccessMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class SendMonitorAccessMail implements ShouldQueue
{
    /**
     * Handle the event.
     */
    public function handle(MonitorAccessTokenCreatedEvent $event): void
    {
        if (config('mail.sending_enabled')) {
            Mail::to($event->monitor->user->email)
                ->send(new MonitorAccessMail($event->monitor, $event->token));
        }
    }
}
