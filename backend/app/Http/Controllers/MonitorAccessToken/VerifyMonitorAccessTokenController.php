<?php

declare(strict_types=1);

namespace App\Http\Controllers\MonitorAccessToken;

use App\Actions\MonitorAccessToken\VerifyMonitorAccessTokenAction;
use App\Http\Controllers\Controller;
use App\Models\MonitorAccessToken;
use Illuminate\Http\JsonResponse;

final class VerifyMonitorAccessTokenController extends Controller
{
    public function __invoke(VerifyMonitorAccessTokenAction $verifyAccessToken, MonitorAccessToken $monitorAccessToken): JsonResponse
    {
        $result = $verifyAccessToken->handle($monitorAccessToken);

        return response()->json($result->toArray());
    }
}
