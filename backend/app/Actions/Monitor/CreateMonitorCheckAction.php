<?php

declare(strict_types=1);

namespace App\Actions\Monitor;

use App\Enums\StatusEnum;
use App\Models\Monitor;
use App\Models\MonitorCheck;
use Carbon\Carbon;

final readonly class CreateMonitorCheckAction
{
    public function handle(Monitor $monitor, ?int $httpCode): void
    {
        $expectedHttpCode = $monitor->expected_http_code->code();

        MonitorCheck::create([
            'monitor_id' => $monitor->id,
            'http_code' => $httpCode,
            'status' => StatusEnum::fromHttpCode($httpCode, $expectedHttpCode),
            'checked_at' => Carbon::now(),
        ]);
    }
}
