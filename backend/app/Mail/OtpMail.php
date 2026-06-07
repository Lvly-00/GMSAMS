<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OtpMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    public function __construct(
        public User $user,
        public string $otp,
        public string $purpose,
    ) {}

    public function envelope(): Envelope
    {
        $subject = match ($this->purpose) {
            'password_reset' => 'GMSAMS Password Reset OTP',
            'email_verification' => 'GMSAMS Email Verification OTP',
            default => 'GMSAMS Verification Code',
        };

        return new Envelope(subject: $subject);
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.otp',
            with: [
                'username' => $this->user->username,
                'otp' => $this->otp,
                'purpose' => $this->purpose,
                'expiryMinutes' => config('gmsams.otp_expiry_minutes', 10),
            ],
        );
    }
}
