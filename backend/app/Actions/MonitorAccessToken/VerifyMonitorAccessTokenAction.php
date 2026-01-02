<?php

declare(strict_types=1);

namespace App\Actions\MonitorAccessToken;

use App\Dto\VerifyAccessTokenResult;
use App\Models\MonitorAccessToken;
use App\Services\AccessTokenVerifier;

final readonly class VerifyMonitorAccessTokenAction
{
    public function handle(MonitorAccessToken $monitorAccessToken): VerifyAccessTokenResult
    {
        $result = AccessTokenVerifier::verify($monitorAccessToken);

        if ($result->isValid) {
            $monitorAccessToken->update(['used_at' => now()]);
        }

        return $result;
    }
}
