<?php

declare(strict_types=1);

namespace App\Http\Controllers\MonitorAccessToken;

use App\Actions\ProcessAccessTokenRefresh;
use App\Http\Controllers\Controller;
use App\Http\Requests\RefreshMonitorAccessTokenRequest;
use App\Models\MonitorAccessToken;
use App\Services\DevTokenHelper;
use Illuminate\Http\JsonResponse;
use Throwable;

final class RefreshMonitorAccessTokenController extends Controller
{
    /**
     * @throws Throwable
     */
    public function __invoke(RefreshMonitorAccessTokenRequest $request, ProcessAccessTokenRefresh $processRefresh, MonitorAccessToken $monitorAccessToken): JsonResponse
    {
        $processRefresh->handle($monitorAccessToken);

        // !!! Caution : must be enabled only for dev purpose !!!
        // Return plain-text token in response when enabled
        if (DevTokenHelper::isEnabled()) {
            return DevTokenHelper::response();
        }

        return response()->json(status: 201);
    }
}
