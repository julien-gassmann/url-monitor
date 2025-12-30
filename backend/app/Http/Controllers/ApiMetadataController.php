<?php

namespace App\Http\Controllers;

use App\Actions\Metadata\GetApiMetadataAction;
use Illuminate\Http\JsonResponse;

class ApiMetadataController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(GetApiMetadataAction $getApiMetadata): JsonResponse
    {
        $metadata = $getApiMetadata->handle();

        return response()->json($metadata);
    }
}
