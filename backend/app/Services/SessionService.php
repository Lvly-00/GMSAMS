<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserSession;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;

class SessionService
{
    public function __construct(
        private readonly ActivityLogService $activityLogService,
    ) {}

    public function create(
        User $user,
        string $plainToken,
        string $ip,
        ?string $userAgent = null
    ): UserSession {
        $timeoutMinutes = (int) config('gmsams.session_timeout_minutes', 15);
        $now = Carbon::now();

        return UserSession::query()->create([
            'user_id' => $user->id,
            'token_hash' => hash('sha256', $plainToken),
            'ip_address' => $ip,
            'user_agent' => $userAgent,
            'last_active' => $now,
            'expires_at' => $now->copy()->addMinutes($timeoutMinutes),
        ]);
    }

    public function touch(UserSession $session): void
    {
        $timeoutMinutes = (int) config('gmsams.session_timeout_minutes', 15);
        $now = Carbon::now();

        $session->update([
            'last_active' => $now,
            'expires_at' => $now->copy()->addMinutes($timeoutMinutes),
        ]);
    }

    public function findByToken(string $plainToken): ?UserSession
    {
        return UserSession::query()
            ->where('token_hash', hash('sha256', $plainToken))
            ->first();
    }

    public function validateActive(UserSession $session): bool
    {
        $timeoutMinutes = (int) config('gmsams.session_timeout_minutes', 15);

        if ($session->isExpired()) {
            return false;
        }

        if ($session->isInactive($timeoutMinutes)) {
            return false;
        }

        return true;
    }

    public function terminate(UserSession $session, ?User $user = null, string $reason = 'logout'): void
    {
        $session->delete();

        if ($user !== null && $reason === 'session_timeout') {
            $this->activityLogService->log(
                user: $user,
                actionType: 'session_timeout',
                moduleName: 'auth',
                description: 'User session expired due to inactivity.',
                sessionId: $session->id,
            );
        }
    }

    public function terminateAllForUser(User $user): void
    {
        UserSession::query()->where('user_id', $user->id)->delete();
    }
}
