<?php

namespace App\Jobs;

use App\Mail\OtpMail;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class SendOtpMailJob implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public string $userId,
        public string $otp,
        public string $purpose,
    ) {}

    public function handle(): void
    {
        $user = User::query()->select('id', 'email', 'username')->findOrFail($this->userId);

        Mail::to($user->email)->send(new OtpMail($user, $this->otp, $this->purpose));
    }
}
