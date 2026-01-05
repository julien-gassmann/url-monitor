<?php

declare(strict_types=1);

namespace App\Http\Controllers\MonitorAccessToken;

use App\Actions\ProcessAccessTokenVerification;
use App\Http\Controllers\Controller;
use App\Models\MonitorAccessToken;
use Illuminate\Http\JsonResponse;
use Throwable;

final class VerifyMonitorAccessTokenController extends Controller
{
    /**
     * @throws Throwable
     */
    public function __invoke(ProcessAccessTokenVerification $processVerification, MonitorAccessToken $monitorAccessToken): JsonResponse
    {
        $result = $processVerification->handle($monitorAccessToken);

        return response()->json($result->toArray(), $result->statusCode());
    }
}
