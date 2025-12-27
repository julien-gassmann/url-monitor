<?php

namespace App\Actions;

use App\Models\User;

final readonly class CreateUserAction
{
    public function handle(string $email): User
    {
        return User::firstOrCreate(['email' => $email]);
    }
}
