<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\HttpCodeEnum;
use Illuminate\Http\JsonResponse;
use Random\RandomException;

class PongController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @throws RandomException
     */
    public function __invoke(): JsonResponse
    {
        $probability = random_int(1, 100);
        $statusCode = match (true) {
            $probability > 20 => 200,
            $probability > 5 => HttpCodeEnum::random()->value,
            default => sleep(10),
        };

        return response()->json('pong', $statusCode ?: HttpCodeEnum::HTTP_I_AM_A_TEAPOT->value);
    }
}
