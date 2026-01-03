<?php

declare(strict_types=1);

namespace App\Actions\User;

use App\Dto\BearerToken;
use App\Dto\VerifyAccessTokenResult;
use App\Models\Monitor;

final readonly class CreateBearerTokenAction
{
    public function handle(VerifyAccessTokenResult $result): VerifyAccessTokenResult
    {
        if (! $result->isValid) {
            return $result;
        }

        /** @var Monitor $monitor */
        $monitor = $result->monitor;
        /** @var string $ttl */
        $ttl = config('sanctum.ttl');
        $expiresAt = now()->addSeconds(intval($ttl)); // Default: 30min

        $token = $monitor->user->createToken(
            name: 'monitor-access',
            abilities: ['view-monitor:'.$monitor->id],
            expiresAt: $expiresAt
        );

        $bearer = new BearerToken(token: $token->plainTextToken, expiresAt: $expiresAt);

        return $result->withBearerToken($bearer);
    }
}
