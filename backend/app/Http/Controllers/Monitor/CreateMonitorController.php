<?php

declare(strict_types=1);

namespace App\Http\Controllers\Monitor;

use App\Actions\Monitor\CreateMonitorAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateMonitorRequest;
use App\Models\Monitor;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Throwable;

class CreateMonitorController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @throws Throwable
     */
    public function __invoke(CreateMonitorRequest $request, CreateMonitorAction $createMonitor): JsonResponse
    {
        $createMonitor->handle($request);

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
