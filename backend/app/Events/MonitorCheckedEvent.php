<?php

declare(strict_types=1);

namespace App\Events;

use App\Models\Monitor;
use Illuminate\Foundation\Events\Dispatchable;

class MonitorCheckedEvent
{
    use Dispatchable;

    /**
     * Create a new event instance.
     */
    public function __construct(
        public Monitor $monitor
    ) {}
}
