<?php

namespace App\Services;

use App\Models\LoginAttempt;
use App\Models\PasswordResetHistory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use App\Jobs\Auth\HandleFailedLoginJob;
use App\Jobs\Auth\HandleSuccessfulLoginJob;
use App\Jobs\Auth\LogSecurityEventJob;
use App\Jobs\Auth\RecordLoginAttemptJob;

class AuthService
{
    public function __construct(
        private readonly ActivityLogService $activityLogService,
        private readonly SessionService $sessionService,
        private readonly OtpService $otpService,
    ) {}

    public function attemptLogin(string $login, string $password, bool $remember, Request $request): array
    {
        $user = User::query()
            ->with('role:id,name')
            ->where('username', $login)
            ->orWhere('email', $login)
            ->first();

        if (! $user) {
            dispatch(new RecordLoginAttemptJob(null, $login, false, $request->ip()));
            throw ValidationException::withMessages(['login' => ['Invalid credentials.']]);
        }

        if (! $user->is_active) {
            throw ValidationException::withMessages(['login' => ['This account has been deactivated.']]);
        }

        if ($user->isLocked()) {
            dispatch(new LogSecurityEventJob($user->id, 'lockout_attempt', $request->ip()));

            throw ValidationException::withMessages(['login' => ['Account is temporarily locked.']]);
        }

        if (! Hash::check($password, $user->password_hash)) {
            dispatch(new HandleFailedLoginJob($user->id, $request->ip(), $login));
            throw ValidationException::withMessages(['login' => ['Invalid credentials.']]);
        }

        // ⚡ ONLY CRITICAL WRITE
        $user->update([
            'failed_attempts' => 0,
            'locked_until' => null,
            'last_login_at' => now(),
        ]);

        // Sanctum token (FAST + REQUIRED)
        $token = $user->createToken(
            'spa-' . Str::uuid()
        )->plainTextToken;

        // async side effects
        dispatch(new HandleSuccessfulLoginJob($user->id, $token, $request->ip()));

        return [
            'user' => $user,
            'token' => $token,
        ];
    }

    public function logout(User $user, Request $request, ?string $plainToken = null): void
    {
        if ($plainToken !== null) {
            $session = $this->sessionService->findByToken($plainToken);
            if ($session !== null) {
                $this->sessionService->terminate($session, $user);
            }
        } else {
            $this->sessionService->terminateAllForUser($user);
        }

        $user->tokens()->delete();

        $this->activityLogService->log(
            user: $user,
            actionType: 'logout',
            moduleName: 'auth',
            description: 'User logged out.',
            request: $request,
        );

        Auth::logout();
    }

    public function sendOtp(string $login, string $purpose, Request $request): User
    {
        $user = User::query()
            ->where('username', $login)
            ->orWhere('email', $login)
            ->first();

        if ($user === null) {
            throw ValidationException::withMessages([
                'login' => ['No account found with that username or email.'],
            ]);
        }

        if (! $user->is_active) {
            throw ValidationException::withMessages([
                'login' => ['This account has been deactivated.'],
            ]);
        }

        $this->otpService->generateAndSend($user, $purpose);

        if ($purpose === 'password_reset') {
            $this->activityLogService->log(
                user: $user,
                actionType: 'password_reset',
                moduleName: 'auth',
                description: 'Password reset OTP requested.',
                request: $request,
            );
        }

        return $user;
    }

    public function sendPasswordResetOtp(string $login, Request $request): User
    {
        return $this->sendOtp($login, 'password_reset', $request);
    }

    public function resetPassword(User $user, string $otp, string $password, Request $request): void
    {
        if (! $this->otpService->validatePassword($password)) {
            throw ValidationException::withMessages([
                'password' => ['Password must be 6–18 characters with at least one uppercase letter, one lowercase letter, and one number. No spaces allowed.'],
            ]);
        }

        $this->otpService->verify($user, 'password_reset', $otp);

        DB::transaction(function () use ($user, $password, $request) {
            $user->update([
                'password_hash' => $password,
                'failed_attempts' => 0,
                'locked_until' => null,
            ]);

            PasswordResetHistory::query()->create([
                'user_id' => $user->id,
                'reset_at' => Carbon::now(),
                'ip_address' => $request->ip(),
            ]);

            $this->sessionService->terminateAllForUser($user);
            $user->tokens()->delete();

            $this->activityLogService->log(
                user: $user,
                actionType: 'password_reset',
                moduleName: 'auth',
                description: 'Password reset completed successfully.',
                request: $request,
            );
        });
    }

    private function handleFailedLogin(User $user, Request $request): void
    {
        $maxAttempts = (int) config('gmsams.max_login_attempts', 5);
        $failedAttempts = $user->failed_attempts + 1;

        $updates = ['failed_attempts' => $failedAttempts];

        if ($failedAttempts >= $maxAttempts) {
            $lockCount = $user->lock_count + 1;
            $durations = config('gmsams.lockout_durations');
            $durationKey = min($lockCount, max(array_keys($durations)));
            $lockMinutes = $durations[$durationKey];

            $updates['lock_count'] = $lockCount;
            $updates['locked_until'] = Carbon::now()->addMinutes($lockMinutes);
            $updates['failed_attempts'] = 0;

            $this->activityLogService->log(
                user: $user,
                actionType: 'security_lockout',
                moduleName: 'auth',
                description: "Account locked for {$lockMinutes} minutes after {$maxAttempts} failed attempts.",
                request: $request,
            );
        }

        $user->update($updates);

        $this->activityLogService->log(
            user: $user,
            actionType: 'failed_login',
            moduleName: 'auth',
            description: 'Failed login attempt.',
            request: $request,
        );
    }

    private function recordLoginAttempt(?User $user, string $usernameTried, bool $succeeded, Request $request): void
    {
        LoginAttempt::query()->create([
            'user_id' => $user?->id,
            'username_tried' => $usernameTried,
            'ip_address' => $request->ip(),
            'succeeded' => $succeeded,
            'attempted_at' => Carbon::now(),
        ]);
    }
}
