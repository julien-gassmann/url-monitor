<?php

namespace Database\Seeders;

use App\Models\Monitor;
use App\Models\MonitorAccessToken;
use App\Models\MonitorCheck;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        /** @var User $user */
        $user = User::factory()->create();

        /** @var Monitor $monitor */
        $monitor = Monitor::factory()->create(['user_id' => $user->id]);

        MonitorAccessToken::factory()->create(['monitor_id' => $monitor->id]);
        MonitorCheck::factory()->create(['monitor_id' => $monitor->id]);
    }
}
