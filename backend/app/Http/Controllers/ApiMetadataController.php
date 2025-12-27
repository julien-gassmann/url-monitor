<?php

namespace App\Http\Controllers;

use App\Actions\GetApiMetadataAction;
use Illuminate\Http\JsonResponse;

class ApiMetadataController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(GetApiMetadataAction $getApiMetadataAction): JsonResponse
    {
        $metadata = $getApiMetadataAction->handle();

        return response()->json($metadata);
    }
}
