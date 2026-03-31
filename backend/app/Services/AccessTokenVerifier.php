<?php

declare(strict_types=1);

namespace App\Services;

use App\Dto\VerifyAccessTokenResult;
use App\Enums\InvalidReasonEnum;
use App\Models\MonitorAccessToken;

abstract class AccessTokenVerifier
{
    public static function generate(?int $id = null): string
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $token = bin2hex(random_bytes(32));

        // Keep access to plain-text token
        if (DevTokenHelper::isEnabled()) {
            DevTokenHelper::putInCache($id, $token);
        }

        return $token;
    }

    public static function hash(string $token): string
    {
        return hash('sha256', $token);
    }

    public static function verify(MonitorAccessToken $monitorAccessToken): VerifyAccessTokenResult
    {
        return match (true) {
            $monitorAccessToken->used_at !== null => new VerifyAccessTokenResult(false, InvalidReasonEnum::AlreadyUsed, null),
            $monitorAccessToken->expires_at->isPast() => new VerifyAccessTokenResult(false, InvalidReasonEnum::Expired, null),
            default => new VerifyAccessTokenResult(true, null, $monitorAccessToken->monitor),
        };
    }
}
