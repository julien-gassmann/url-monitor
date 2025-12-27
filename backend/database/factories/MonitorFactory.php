<?php

namespace Database\Factories;

use App\Enums\FrequencyEnum;
use App\Models\Monitor;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Monitor>
 */
class MonitorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'url' => fake()->url(),
            'expected_http_code' => 200,
            'frequency' => FrequencyEnum::DAILY,
        ];
    }
}
