<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\HttpCodeEnum;
use App\Services\RandomPing;
use Illuminate\Http\JsonResponse;

class PongController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(RandomPing $ping): JsonResponse
    {

        $probability = random_int(1, 100);
        $statusCode = $ping->pong($probability) ?: HttpCodeEnum::HTTP_I_AM_A_TEAPOT->value;

        return response()->json(['data' => 'pong'], $statusCode);
    }
}
