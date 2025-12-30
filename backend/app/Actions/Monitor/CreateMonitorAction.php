<?php

namespace App\Actions\Monitor;

use App\Actions\User\CreateUserAction;
use App\Http\Requests\CreateMonitorRequest;
use App\Models\Monitor;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ValidatedInput;
use Throwable;

final readonly class CreateMonitorAction
{
    public function __construct(
        private CreateUserAction $createUser,
    ) {}

    /**
     * @throws Throwable
     */
    public function handle(CreateMonitorRequest $request): void
    {
        /** @var ValidatedInput $safeRequest */
        $safeRequest = $request->safe();

        DB::transaction(function () use ($safeRequest): void {
            $email = $safeRequest->string('user_email');
            $user = $this->createUser->handle($email);

            Monitor::create([
                ...$safeRequest->except('user_email'),
                'user_id' => $user->id,
                'next_check_at' => now(),
            ]);
        });
    }
}
