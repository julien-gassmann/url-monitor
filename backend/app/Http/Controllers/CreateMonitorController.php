<?php

namespace App\Http\Controllers;

use App\Actions\CreateMonitorAction;
use App\Http\Requests\CreateMonitorRequest;
use App\Http\Resources\MonitorResource;
use Throwable;

class CreateMonitorController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @throws Throwable
     */
    public function __invoke(CreateMonitorRequest $request, CreateMonitorAction $createMonitorAction): MonitorResource
    {
        $monitor = $createMonitorAction->handle($request);

        return MonitorResource::make($monitor->load('user'));
    }
}
