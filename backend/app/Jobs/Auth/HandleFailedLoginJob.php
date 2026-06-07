<?php

namespace App\Jobs\Auth;

use App\Models\User;
use App\Services\ActivityLogService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class HandleFailedLoginJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public int $userId,
        public string $ip,
        public string $login
    ) {}

    public function handle(ActivityLogService $log): void
    {
        $user = User::find($this->userId);

        if (! $user) return;

        $user->increment('failed_attempts');

        $log->log(
            user: $user,
            actionType: 'failed_login',
            moduleName: 'auth',
            description: 'Failed login attempt.',
        );
    }
}