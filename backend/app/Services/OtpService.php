<?php

namespace App\Services;

use App\Jobs\SendOtpMailJob;
use App\Models\OtpVerification;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class OtpService
{
    public function __construct(
        private readonly ActivityLogService $activityLogService,
    ) {}

    public function generateAndSend(User $user, string $purpose): void
    {
        $expiryMinutes = (int) config('gmsams.otp_expiry_minutes', 10);
        $maxResends = (int) config('gmsams.otp_max_resends', 3);

        DB::transaction(function () use ($user, $purpose, $expiryMinutes, $maxResends) {
            OtpVerification::query()
                ->where('user_id', $user->id)
                ->where('purpose', $purpose)
                ->where('is_used', false)
                ->update(['is_used' => true]);

            $lastOtp = OtpVerification::query()
                ->where('user_id', $user->id)
                ->where('purpose', $purpose)
                ->orderByDesc('id')
                ->first();

            $resendCount = ($lastOtp?->resend_count ?? -1) + 1;

            if ($resendCount > $maxResends) {
                abort(429, 'Maximum OTP resend limit reached. Please try again later.');
            }

            $otp = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

            OtpVerification::query()->create([
                'user_id' => $user->id,
                'purpose' => $purpose,
                'otp_hash' => Hash::make($otp),
                'resend_count' => $resendCount,
                'max_resends' => $maxResends,
                'is_used' => false,
                'expires_at' => Carbon::now()->addMinutes($expiryMinutes),
            ]);

            SendOtpMailJob::dispatch($user->id, $otp, $purpose);
        });
    }

    public function verify(User $user, string $purpose, string $otp): OtpVerification
    {
        $record = OtpVerification::query()
            ->where('user_id', $user->id)
            ->where('purpose', $purpose)
            ->where('is_used', false)
            ->latest('id')
            ->first();

        if ($record === null || ! $record->isValid()) {
            abort(422, 'OTP is invalid or has expired.');
        }

        if (! Hash::check($otp, $record->otp_hash)) {
            abort(422, 'Incorrect OTP. Please try again.');
        }

        $record->update(['is_used' => true]);

        $this->activityLogService->log(
            user: $user,
            actionType: 'otp_verified',
            moduleName: 'auth',
            description: "OTP verified for {$purpose}.",
        );

        return $record;
    }

    public function validatePassword(string $password): bool
    {
        $pattern = config('gmsams.password.pattern');

        return (bool) preg_match($pattern, $password);
    }
}
