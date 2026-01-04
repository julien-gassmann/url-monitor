<?php

declare(strict_types=1);

namespace App\Http\Controllers\Metadata;

use App\Actions\Metadata\GetApiMetadataAction;
use App\Enums\MetadataPageEnum;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class ApiMetadataController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(GetApiMetadataAction $getApiMetadata, MetadataPageEnum $page): JsonResponse
    {
        $metadata = $getApiMetadata->handle($page);

        return response()->json($metadata);
    }
}
