<?php

declare(strict_types=1);

namespace App\Http\Controllers\Monitor;

use App\Http\Controllers\Controller;
use App\Http\Requests\ShowMonitorRequest;
use App\Http\Resources\MonitorResource;
use App\Models\Monitor;

class ShowMonitorController extends Controller
{
    public function __invoke(ShowMonitorRequest $request, Monitor $monitor): MonitorResource
    {
        return MonitorResource::make($monitor->load('user'));
    }
}
