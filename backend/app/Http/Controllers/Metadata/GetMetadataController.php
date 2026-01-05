<?php

declare(strict_types=1);

namespace App\Http\Controllers\Metadata;

use App\Actions\Metadata\GetMetadataAction;
use App\Enums\MetadataPageEnum;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class GetMetadataController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(GetMetadataAction $getMetadata, MetadataPageEnum $page): JsonResponse
    {
        $metadata = $getMetadata->handle($page);

        return response()->json($metadata);
    }
}
