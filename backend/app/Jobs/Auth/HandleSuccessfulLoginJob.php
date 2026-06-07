<?php

namespace App\Jobs\Auth;

use App\Models\User;
use App\Services\ActivityLogService;
use App\Services\SessionService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\Request;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class HandleSuccessfulLoginJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public string $userId,
        public string $token,
        public string $ip
    ) {}

    public function handle(ActivityLogService $activityLog, SessionService $sessionService): void
    {
        $user = User::find($this->userId);
        if (! $user) return;

        // Perform the DB writes here, in the background
        $user->update([
            'failed_attempts' => 0,
            'locked_until' => null,
            'last_login_at' => now(),
        ]);

        $session = $sessionService->create($user, $this->token, $this->ip);

        $activityLog->log(
            user: $user,
            actionType: 'login',
            moduleName: 'auth',
            description: 'User logged in successfully.',
            sessionId: $session->id,
        );
    }
}
