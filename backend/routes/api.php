<?php

use App\Http\Controllers\Metadata\GetMetadataController;
use App\Http\Controllers\Monitor\CreateMonitorController;
use App\Http\Controllers\Monitor\PaginateMonitorChecksController;
use App\Http\Controllers\Monitor\ShowMonitorController;
use App\Http\Controllers\Monitor\ValidateMonitorFieldController;
use App\Http\Controllers\MonitorAccessToken\RefreshMonitorAccessTokenController;
use App\Http\Controllers\MonitorAccessToken\VerifyMonitorAccessTokenController;
use App\Http\Controllers\PongController;
use Illuminate\Support\Facades\Route;

Route::get('/metadata/{page}', GetMetadataController::class)->name('api.metadata');

Route::prefix('monitors')->group(fn (): array => [
    Route::post('/', CreateMonitorController::class)->name('monitors.create'),
    Route::post('/validate', ValidateMonitorFieldController::class)->name('monitors.validate'),
    Route::middleware('auth:sanctum')->group(fn (): array => [
        Route::get('/{monitor:uuid}', ShowMonitorController::class)->name('monitors.show'),
        Route::get('/{monitor:uuid}/checks', PaginateMonitorChecksController::class)->name('monitors.checks.paginate'),
    ]),
]);

Route::prefix('tokens')->group(fn (): array => [
    Route::get('/verify/{token}', VerifyMonitorAccessTokenController::class)->name('tokens.verify'),
    Route::get('/refresh/{token}', RefreshMonitorAccessTokenController::class)->name('tokens.refresh'),
]);

// Fake route
Route::get('/ping', PongController::class)->name('ping.pong');
