<?php

declare(strict_types=1);

namespace App\Http\Controllers\Monitor;

use App\Actions\Monitor\CreateMonitorAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateMonitorRequest;
use App\Services\DevTokenHelper;
use Illuminate\Http\JsonResponse;
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
        if (DevTokenHelper::isEnabled()) {
            return DevTokenHelper::response();
        }

        return response()->json(status: 201);
    }
}
