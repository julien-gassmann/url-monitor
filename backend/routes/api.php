<?php

use App\Http\Controllers\ApiMetadataController;
use App\Http\Controllers\CreateMonitorController;
use Illuminate\Support\Facades\Route;

Route::get('/metadata', ApiMetadataController::class)->name('api.metadata');
Route::post('/monitors', CreateMonitorController::class)->name('create.monitor');
