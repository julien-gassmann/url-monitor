<?php

namespace App\Http\Controllers;

use App\Actions\Monitor\CreateMonitorAction;
use App\Http\Requests\CreateMonitorRequest;
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

        return response()->json(status: 201);
    }
}
