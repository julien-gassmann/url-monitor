<?php

declare(strict_types=1);

namespace App\Actions\MonitorAccessToken;

use App\Models\Monitor;

final readonly class InvalidateMonitorAccessTokensAction
{
    public function onVerify(Monitor $monitor): void
    {
        $monitor->accessTokens()
            ->whereNull('used_at')
            ->update(['used_at' => now()]);
    }

    public function onRefresh(Monitor $monitor): void
    {
        $monitor->accessTokens()
            ->whereNull('refreshed_at')
            ->update([
                'used_at' => now(),
                'refreshed_at' => now(),
            ]);
    }
}
