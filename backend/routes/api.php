<?php

use App\Http\Controllers\ApiMetadataController;
use App\Http\Controllers\CreateMonitorController;
use App\Http\Controllers\ValidateMonitorFieldController;
use Illuminate\Support\Facades\Route;

Route::get('/metadata', ApiMetadataController::class)->name('api.metadata');

Route::post('/monitors/validate', ValidateMonitorFieldController::class)->name('monitors.validate');
Route::post('/monitors', CreateMonitorController::class)->name('monitors.create');
