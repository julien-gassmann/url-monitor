<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\MonitorCheckedEvent;
use App\Mail\MonitorCheckedMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class SendMonitorCheckNotification implements ShouldQueue
{
    /**
     * Handle the event.
     */
    public function handle(MonitorCheckedEvent $event): void
    {
        Mail::to($event->monitor->user->email)
            ->send(new MonitorCheckedMail($event->monitor));
    }
}
