<?php

namespace App\Jobs\Auth;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\LoginAttempt;

class RecordLoginAttemptJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public ?int $userId,
        public string $username,
        public bool $success,
        public string $ip
    ) {}

    public function handle(): void
    {
        LoginAttempt::create([
            'user_id' => $this->userId,
            'username_tried' => $this->username,
            'ip_address' => $this->ip,
            'succeeded' => $this->success,
            'attempted_at' => now(),
        ]);
    }
}