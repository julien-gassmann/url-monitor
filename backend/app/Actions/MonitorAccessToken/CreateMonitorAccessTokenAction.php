<?php

declare(strict_types=1);

namespace App\Actions\MonitorAccessToken;

use App\Events\MonitorAccessTokenCreatedEvent;
use App\Models\Monitor;
use App\Services\AccessTokenVerifier;

final readonly class CreateMonitorAccessTokenAction
{
    public function handle(Monitor $monitor): void
    {
        $token = AccessTokenVerifier::generate($monitor->id);

        $monitor->accessTokens()->create([
            'token_hash' => AccessTokenVerifier::hash($token),
            'expires_at' => now()->addMinutes(5),
        ]);

        event(new MonitorAccessTokenCreatedEvent($monitor, $token));
    }
}
