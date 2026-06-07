<?php

namespace App\Services;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ActivityLogService
{
    public function log(
        ?User $user,
        string $actionType,
        string $moduleName,
        string $description,
        ?array $oldValues = null,
        ?array $newValues = null,
        ?Request $request = null,
        ?int $sessionId = null,
    ): ActivityLog {
        $request ??= request();

        return ActivityLog::query()->create([
            'user_id' => $user?->id,
            'role_id' => $user?->role_id,
            'action_type' => $actionType,
            'module_name' => $moduleName,
            'description' => $description,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'device_type' => $this->detectDeviceType($request->userAgent()),
            'browser' => $this->detectBrowser($request->userAgent()),
            'operating_system' => $this->detectOs($request->userAgent()),
            'session_id' => $sessionId,
            'created_at' => Carbon::now(),
        ]);
    }

    private function detectDeviceType(?string $userAgent): ?string
    {
        if ($userAgent === null) {
            return null;
        }

        if (preg_match('/mobile|android|iphone|ipad/i', $userAgent)) {
            return 'mobile';
        }

        if (preg_match('/tablet/i', $userAgent)) {
            return 'tablet';
        }

        return 'desktop';
    }

    private function detectBrowser(?string $userAgent): ?string
    {
        if ($userAgent === null) {
            return null;
        }

        $patterns = [
            'Edge' => '/Edg\//',
            'Chrome' => '/Chrome\//',
            'Firefox' => '/Firefox\//',
            'Safari' => '/Safari\//',
        ];

        foreach ($patterns as $name => $pattern) {
            if (preg_match($pattern, $userAgent)) {
                return $name;
            }
        }

        return 'Unknown';
    }

    private function detectOs(?string $userAgent): ?string
    {
        if ($userAgent === null) {
            return null;
        }

        $patterns = [
            'Windows' => '/Windows/i',
            'macOS' => '/Mac OS/i',
            'Linux' => '/Linux/i',
            'Android' => '/Android/i',
            'iOS' => '/iPhone|iPad/i',
        ];

        foreach ($patterns as $name => $pattern) {
            if (preg_match($pattern, $userAgent)) {
                return $name;
            }
        }

        return 'Unknown';
    }
}
