<?php

use App\Http\Controllers\CreateMonitorController;
use Illuminate\Support\Facades\Route;

Route::post('/monitors', CreateMonitorController::class)->name('create.monitor');
