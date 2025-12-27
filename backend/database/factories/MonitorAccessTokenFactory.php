<?php

namespace Database\Factories;

use App\Models\Monitor;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<Monitor>
 */
class MonitorAccessTokenFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'token_hash' => Hash::make(Str::random(60)),
            'expires_at' => Carbon::now()->addMinutes(5),
            'used_at' => Carbon::now()->addMinute(),
        ];
    }
}
