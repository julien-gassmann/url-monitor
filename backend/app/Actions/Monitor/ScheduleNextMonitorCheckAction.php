<?php

declare(strict_types=1);

namespace App\Actions\Monitor;

use App\Enums\FrequencyEnum;
use App\Models\Monitor;

final readonly class ScheduleNextMonitorCheckAction
{
    public function handle(Monitor $monitor): void
    {
        $shouldTravelTime = config('app.time_traveller_mode_enabled');

        if ($shouldTravelTime) {
            $nextCheck = match ($monitor->frequency) {
                FrequencyEnum::DAILY => now()->addMinute(),
                FrequencyEnum::WEEKLY => now()->addMinutes(5),
                FrequencyEnum::MONTHLY => now()->addMinutes(10),
            };
        } else {
            $nextCheck = match ($monitor->frequency) {
                FrequencyEnum::DAILY => now()->addDay(),
                FrequencyEnum::WEEKLY => now()->addWeek(),
                FrequencyEnum::MONTHLY => now()->addMonth(),
            };
        }

        $monitor->update(['next_check_at' => $nextCheck]);
    }
}
