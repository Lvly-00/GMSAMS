<?php

namespace App\Services\Admin;

use App\Models\ActivityLog;
use App\Models\ClassRecord;
use App\Models\EnrollmentRecord;
use App\Models\Role;
use App\Models\Section;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class AdminDashboardService
{
    public function getStats(): array
    {
        return Cache::remember('admin.dashboard.stats', 300, function () {
            $roleCounts = User::query()
                ->select('role_id', DB::raw('count(*) as total'))
                ->whereNull('deleted_at')
                ->groupBy('role_id')
                ->pluck('total', 'role_id');

            $roles = Role::query()->select('id', 'name')->get()->keyBy('id');

            $usersByRole = [
                'students' => (int) ($roleCounts[$roles->firstWhere('name', 'student')?->id] ?? 0),
                'teachers' => (int) ($roleCounts[$roles->firstWhere('name', 'teacher')?->id] ?? 0),
                'head_teachers' => (int) ($roleCounts[$roles->firstWhere('name', 'head_teacher')?->id] ?? 0),
                'admins' => (int) ($roleCounts[$roles->firstWhere('name', 'admin')?->id] ?? 0),
            ];

            $activeCount = User::query()->where('is_active', true)->whereNull('deleted_at')->count();
            $inactiveCount = User::query()->where('is_active', false)->whereNull('deleted_at')->count();

            return [
                'users_by_role' => $usersByRole,
                'account_status' => [
                    'active' => $activeCount,
                    'inactive' => $inactiveCount,
                ],
                'usage' => [
                    'subjects' => Subject::query()->whereNull('deleted_at')->count(),
                    'sections' => Section::query()->count(),
                    'enrollments' => EnrollmentRecord::query()->where('status', 'active')->count(),
                    'class_records' => ClassRecord::query()->whereNull('deleted_at')->count(),
                ],
            ];
        });
    }

    public function getActivityFeed(int $perPage = 15): LengthAwarePaginator
    {
        return ActivityLog::query()
            ->select('id', 'user_id', 'role_id', 'action_type', 'module_name', 'description', 'created_at')
            ->with([
                'user:id,username',
                'role:id,name',
            ])
            ->orderByDesc('created_at')
            ->paginate($perPage);
    }
}
