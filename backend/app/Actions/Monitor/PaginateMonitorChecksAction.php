<?php

declare(strict_types=1);

namespace App\Actions\Monitor;

use App\Http\Requests\PaginateMonitorChecksRequest;
use App\Models\Monitor;
use App\Models\MonitorCheck;
use Illuminate\Pagination\LengthAwarePaginator;
use InvalidArgumentException;

final readonly class PaginateMonitorChecksAction
{
    /**
     * @return LengthAwarePaginator<int, MonitorCheck>
     *
     * @throws InvalidArgumentException
     */
    public function handle(PaginateMonitorChecksRequest $request, Monitor $monitor): LengthAwarePaginator
    {
        $max = $monitor->checks()->count();
        $perPage = MonitorCheck::perPage($request, $max);
        $page = $request->integer('page', 1);

        return MonitorCheck::whereMatchesRequest()
            ->where('monitor_id', $monitor->id)
            ->paginate(
                perPage: $perPage,
                page: $page,
            );
    }
}
