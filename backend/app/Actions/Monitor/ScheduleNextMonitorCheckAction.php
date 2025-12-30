<?php

declare(strict_types=1);

namespace App\Actions\Monitor;

use App\Enums\FrequencyEnum;
use App\Models\Monitor;
use Carbon\Carbon;

final readonly class ScheduleNextMonitorCheckAction
{
    public function handle(Monitor $monitor): void
    {
        $nextCheck = match ($monitor->frequency) {
            FrequencyEnum::DAILY => Carbon::now()->addDay(),
            FrequencyEnum::WEEKLY => Carbon::now()->addWeek(),
            FrequencyEnum::MONTHLY => Carbon::now()->addMonth(),
        };

        $monitor->update(['next_check_at' => $nextCheck]);
    }
}
