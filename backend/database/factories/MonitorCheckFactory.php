<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\StatusEnum;
use App\Models\Monitor;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Monitor>
 */
class MonitorCheckFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'http_code' => 200,
            'status' => StatusEnum::UP,
            'checked_at' => Carbon::now(),
        ];
    }
}
