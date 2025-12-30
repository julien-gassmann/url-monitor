<?php

namespace App\Actions;

use App\Http\Requests\CreateMonitorRequest;
use App\Models\Monitor;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ValidatedInput;
use Throwable;

final readonly class CreateMonitorAction
{
    public function __construct(
        private CreateUserAction $createUserAction,
    ) {}

    /**
     * @throws Throwable
     */
    public function handle(CreateMonitorRequest $request): Monitor
    {
        /** @var ValidatedInput $safeRequest */
        $safeRequest = $request->safe();

        /** @var Monitor $monitor */
        $monitor = DB::transaction(function () use ($safeRequest): Monitor {
            $email = $safeRequest->string('user_email');
            $user = $this->createUserAction->handle($email);

            return Monitor::create([
                ...$safeRequest->except('user_email'),
                'user_id' => $user->id,
            ]);
        });

        return $monitor;
    }
}
