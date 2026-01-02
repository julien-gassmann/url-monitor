<?php

declare(strict_types=1);

namespace App\Http\Controllers\MonitorAccessToken;

use App\Actions\MonitorAccessToken\CreateMonitorAccessTokenAction;
use App\Http\Controllers\Controller;
use App\Models\MonitorAccessToken;
use App\Services\DevTokenHelper;
use Illuminate\Http\JsonResponse;

final class RefreshMonitorAccessTokenController extends Controller
{
    public function __invoke(CreateMonitorAccessTokenAction $createAccessToken, MonitorAccessToken $monitorAccessToken): JsonResponse
    {
        $createAccessToken->handle($monitorAccessToken->monitor);

        // !!! Caution : must be enabled only for dev purpose !!!
        // Return plain-text token in response when enabled
        if (DevTokenHelper::isEnabled()) {
            return DevTokenHelper::response();
        }

        return response()->json(status: 201);
    }
}
