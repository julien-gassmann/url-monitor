<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Enums\FrequencyEnum;
use App\Enums\HttpCodeEnum;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property string $uuid
 * @property string $url
 * @property HttpCodeEnum $expected_http_code
 * @property FrequencyEnum $frequency
 */
class MonitorResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'uuid' => $this->uuid,
            'url' => $this->url,
            'expected_http_code' => $this->expected_http_code->code(),
            'frequency' => $this->frequency->label(),
            'user' => UserResource::make($this->whenLoaded('user')),
        ];
    }
}
