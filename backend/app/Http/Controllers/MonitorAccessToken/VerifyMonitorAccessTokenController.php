<?php

declare(strict_types=1);

namespace App\Http\Controllers\MonitorAccessToken;

use App\Actions\MonitorAccessToken\VerifyMonitorAccessTokenAction;
use App\Actions\User\CreateBearerTokenAction;
use App\Http\Controllers\Controller;
use App\Models\MonitorAccessToken;
use Illuminate\Http\JsonResponse;

final class VerifyMonitorAccessTokenController extends Controller
{
    public function __invoke(VerifyMonitorAccessTokenAction $verifyAccessToken, CreateBearerTokenAction $createBearerToken, MonitorAccessToken $monitorAccessToken): JsonResponse
    {
        $result = $verifyAccessToken->handle($monitorAccessToken);
        $bearer = $createBearerToken->handle($result);
        $content = [...$result->toArray(), ...$bearer->toArray()];

        return response()->json(
            $result->isValid ? $content : ['errors' => $content],
            $result->statusCode()
        );
    }
}
