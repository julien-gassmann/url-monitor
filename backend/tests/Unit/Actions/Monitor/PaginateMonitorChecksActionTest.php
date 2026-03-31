<?php

use App\Actions\Monitor\PaginateMonitorChecksAction;
use App\Http\Requests\PaginateMonitorChecksRequest;
use App\Models\MonitorCheck;

use function Jgss\LaravelPestScenarios\databaseSetup;

/**
 * ───────────────────────────────────────
 * Valid scenarios for Action PaginateMonitorChecksAction
 * ───────────────────────────────────────
 */
describe('Actions - PaginateMonitorChecksAction : success', function (): void {
    it('paginates monitor checks according to incoming request content', function (int $page, int $perPage, string $sort): void {
        // Arrange: Fill database and get monitor
        databaseSetup('create_checks');
        $monitor = queryMonitor('monitor');

        // Arrange: Create payload
        $request = PaginateMonitorChecksRequest::create('', parameters: [
            'page' => $page,
            'per_page' => $perPage,
            'sort' => $sort,
        ]);

        // Arrange: Get expected pagination
        $direction = str_starts_with($sort, '-') ? 'desc' : 'asc';
        $column = ltrim($sort, '-');
        if ($perPage === -1) {
            $perPage = $monitor->checks->count();
        }
        $expectedPagination = MonitorCheck::where('monitor_id', $monitor->id)
            ->orderBy($column, $direction)
            ->paginate(perPage: $perPage, page: $page);

        // Act: Call action's handle method
        /** @noinspection PhpUnhandledExceptionInspection */
        $pagination = app(PaginateMonitorChecksAction::class)->handle($request, $monitor);

        // Assert: Pagination query corresponds to the expected one
        expect($pagination)->toEqual($expectedPagination);
    })->with([
        // ───── status ─────
        'status asc | page 1 | 5 per page' => [
            'page' => 1,
            'perPage' => 5,
            'sort' => 'status',
        ],
        'status desc | page 2 | 5 per page' => [
            'page' => 2,
            'perPage' => 5,
            'sort' => '-status',
        ],

        // ───── http_code ─────
        'http_code asc | page 1 | 10 per page' => [
            'page' => 1,
            'perPage' => 10,
            'sort' => 'http_code',
        ],
        'http_code desc | page 2 | 10 per page' => [
            'page' => 2,
            'perPage' => 10,
            'sort' => '-http_code',
        ],

        // ───── checked_at ─────
        'checked_at asc | page 1 | -1 per page' => [
            'page' => 1,
            'perPage' => -1,
            'sort' => 'checked_at',
        ],
        'checked_at desc | page 1 | -1 per page' => [
            'page' => -1,
            'perPage' => -1,
            'sort' => '-checked_at',
        ],
    ]);
});
