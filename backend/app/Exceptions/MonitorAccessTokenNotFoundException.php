<?php

declare(strict_types=1);

namespace App\Exceptions;

use App\Dto\BearerToken;
use App\Dto\VerifyAccessTokenResult;
use App\Enums\InvalidReasonEnum;
use Illuminate\Http\JsonResponse;
use RuntimeException;

class MonitorAccessTokenNotFoundException extends RuntimeException
{
    public function render(): JsonResponse
    {
        $result = new VerifyAccessTokenResult(isValid: false, reason: InvalidReasonEnum::NotFound, monitor: null);
        $bearer = new BearerToken;

        return response()->json(['errors' => [
            ...$result->toArray(),
            ...$bearer->toArray(),
        ]], 404);
    }
}
