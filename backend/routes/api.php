<?php

use App\Http\Controllers\Metadata\ApiMetadataController;
use App\Http\Controllers\Monitor\CreateMonitorController;
use App\Http\Controllers\Monitor\ShowMonitorController;
use App\Http\Controllers\Monitor\ValidateMonitorFieldController;
use App\Http\Controllers\MonitorAccessToken\RefreshMonitorAccessTokenController;
use App\Http\Controllers\MonitorAccessToken\VerifyMonitorAccessTokenController;
use Illuminate\Support\Facades\Route;

Route::get('/metadata', ApiMetadataController::class)->name('api.metadata');

Route::prefix('monitors')->group(fn (): array => [
    Route::post('/', CreateMonitorController::class)->name('monitors.create'),
    Route::post('/validate', ValidateMonitorFieldController::class)->name('monitors.validate'),
    Route::middleware('auth:sanctum')->get('/{monitor:uuid}', ShowMonitorController::class)->name('monitors.show'),
]);

Route::prefix('tokens')->group(fn (): array => [
    Route::get('/verify/{token}', VerifyMonitorAccessTokenController::class)->name('tokens.verify'),
    Route::get('/refresh/{token}', RefreshMonitorAccessTokenController::class)->name('tokens.verify'),
]);
