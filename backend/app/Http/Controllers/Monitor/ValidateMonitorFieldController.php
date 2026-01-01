<?php

declare(strict_types=1);

namespace App\Http\Controllers\Monitor;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateMonitorRequest;
use Illuminate\Http\JsonResponse;

class ValidateMonitorFieldController extends Controller
{
    public function __invoke(CreateMonitorRequest $request): JsonResponse
    {
        return response()->json();
    }
}
