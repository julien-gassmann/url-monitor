<?php

declare(strict_types=1);

namespace App\Actions\User;

use App\Dto\BearerToken;
use App\Dto\VerifyAccessTokenResult;
use App\Models\Monitor;
use InvalidArgumentException;

final readonly class CreateBearerTokenAction
{
    /**
     * @throws InvalidArgumentException
     */
    public function handle(VerifyAccessTokenResult $result): BearerToken
    {
        if (! $result->isValid) {
            return new BearerToken;
        }

        /** @var Monitor $monitor */
        $monitor = $result->monitor;
        $ttl = config()->string('sanctum.ttl');
        $expiresAt = now()->addSeconds(intval($ttl)); // Default: 30min

        $token = $monitor->user->createToken(
            name: 'monitor-access',
            abilities: ['view-monitor:'.$monitor->id],
            expiresAt: $expiresAt
        );

        return new BearerToken(
            accessToken: $token->plainTextToken,
            expiresAt: $expiresAt,
            tokenType: 'Bearer'
        );
    }
}
