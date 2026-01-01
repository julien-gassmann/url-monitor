<?php

declare(strict_types=1);

namespace App\Http\Controllers\Metadata;

use App\Actions\Metadata\GetApiMetadataAction;
use App\Http\Controllers\Controller;
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
