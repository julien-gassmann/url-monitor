<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\CreateMonitorRequest;
use Illuminate\Http\JsonResponse;

class ValidateMonitorFieldController extends Controller
{
    public function __invoke(CreateMonitorRequest $request): JsonResponse
    {
        return response()->json();
    }
}
