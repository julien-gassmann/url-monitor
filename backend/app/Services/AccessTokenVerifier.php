<?php

declare(strict_types=1);

namespace App\Services;

use App\Dto\VerifyAccessTokenResult;
use App\Enums\InvalidReasonEnum;
use App\Models\MonitorAccessToken;
use Illuminate\Support\Str;
use Random\RandomException;

abstract class AccessTokenVerifier
{
    public static function generate(?int $id = null): string
    {
        try {
            $token = bin2hex(random_bytes(32));
        } catch (RandomException) {
            $token = Str::random(64);
        }

        // Keep access to plain-text token across containers through redis cache
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
