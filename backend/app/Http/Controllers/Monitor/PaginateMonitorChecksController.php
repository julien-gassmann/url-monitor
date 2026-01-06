<?php

declare(strict_types=1);

namespace App\Http\Controllers\Monitor;

use App\Actions\Monitor\PaginateMonitorChecksAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\PaginateMonitorChecksRequest;
use App\Http\Resources\MonitorCheckResource;
use App\Models\Monitor;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Pagination\LengthAwarePaginator;

class PaginateMonitorChecksController extends Controller
{
    /**
     * @return AnonymousResourceCollection<LengthAwarePaginator<int, MonitorCheckResource>>
     */
    public function __invoke(PaginateMonitorChecksRequest $request, PaginateMonitorChecksAction $paginateMonitorChecks, Monitor $monitor): AnonymousResourceCollection
    {
        $pagination = $paginateMonitorChecks->handle($request, $monitor);

        return MonitorCheckResource::collection($pagination);
    }
}
