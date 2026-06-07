<?php

namespace App\Jobs\Auth;

use App\Models\User;
use App\Services\ActivityLogService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class LogSecurityEventJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public int $userId,
        public string $event,
        public string $ip
    ) {}

    public function handle(ActivityLogService $log): void
    {
        $user = User::find($this->userId);

        if (! $user) return;

        $log->log(
            user: $user,
            actionType: 'security',
            moduleName: 'auth',
            description: "Security event: {$this->event}",
        );
    }
}