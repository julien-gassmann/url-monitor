<?php

declare(strict_types=1);

namespace App\Http\Controllers\MonitorAccessToken;

use App\Actions\MonitorAccessToken\CreateMonitorAccessTokenAction;
use App\Http\Controllers\Controller;
use App\Models\Monitor;
use App\Models\MonitorAccessToken;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Random\RandomException;

final class RefreshMonitorAccessTokenController extends Controller
{
    /**
     * @throws RandomException
     */
    public function __invoke(CreateMonitorAccessTokenAction $createAccessToken, MonitorAccessToken $monitorAccessToken): JsonResponse
    {
        $createAccessToken->handle($monitorAccessToken->monitor);

        // !!! Caution : must be enabled only for dev purpose !!!
        // Return plain-text token in response when enabled
        if (config('app.keep_access_token_in_cache')) {
            sleep(1); // Wait for job to be done
            $id = Monitor::latest()->firstOrFail()->id;
            $token = Cache::get("dev:last_monitor_token_$id");

            return response()->json(data: ['token' => $token], status: 201);
        }

        return response()->json(status: 201);
    }
}
