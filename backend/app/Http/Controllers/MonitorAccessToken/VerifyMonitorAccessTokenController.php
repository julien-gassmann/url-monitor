<?php

declare(strict_types=1);

namespace App\Http\Controllers\MonitorAccessToken;

use App\Actions\MonitorAccessToken\VerifyMonitorAccessTokenAction;
use App\Actions\User\CreateBearerTokenAction;
use App\Http\Controllers\Controller;
use App\Models\MonitorAccessToken;
use Illuminate\Http\JsonResponse;
use InvalidArgumentException;

final class VerifyMonitorAccessTokenController extends Controller
{
    /**
     * @throws InvalidArgumentException
     */
    public function __invoke(VerifyMonitorAccessTokenAction $verifyAccessToken, CreateBearerTokenAction $createBearerToken, MonitorAccessToken $monitorAccessToken): JsonResponse
    {
        $result = $verifyAccessToken->handle($monitorAccessToken);
        $bearer = $createBearerToken->handle($result);

        return response()->json([
            ...$result->toArray(),
            ...$bearer->toArray(),
        ]);
    }
}
