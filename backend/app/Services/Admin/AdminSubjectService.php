<?php

namespace App\Services\Admin;

use App\Models\SchoolYear;
use App\Models\Section;
use App\Models\Semester;
use App\Models\Subject;
use App\Models\SubjectTeacherAssignment;
use App\Models\User;
use App\Services\ActivityLogService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class AdminSubjectService
{
    public function __construct(
        private readonly ActivityLogService $activityLogService,
        private readonly ReferenceDataService $referenceDataService,
    ) {}

    public function list(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return Subject::query()
            ->select('id', 'grade_level_id', 'strand_id', 'code', 'name', 'is_hidden', 'created_at')
            ->with([
                'gradeLevel:id,name',
                'strand:id,code,name',
            ])
            ->when(isset($filters['grade_level_id']), fn ($q) => $q->where('grade_level_id', $filters['grade_level_id']))
            ->when(isset($filters['strand_id']), fn ($q) => $q->where('strand_id', $filters['strand_id']))
            ->when(isset($filters['is_hidden']), fn ($q) => $q->where('is_hidden', filter_var($filters['is_hidden'], FILTER_VALIDATE_BOOLEAN)))
            ->when(! empty($filters['search']), fn ($q) => $q->where(function ($sq) use ($filters) {
                $sq->where('name', 'like', "%{$filters['search']}%")
                    ->orWhere('code', 'like', "%{$filters['search']}%");
            }))
            ->when(($filters['include_hidden'] ?? false) === false && ! isset($filters['is_hidden']), fn ($q) => $q->where('is_hidden', false))
            ->orderBy('name')
            ->paginate($perPage);
    }

    public function find(string $subjectId): Subject
    {
        return Subject::query()
            ->with([
                'gradeLevel:id,name',
                'strand:id,code,name',
                'teacherAssignments.teacher:id,first_name,last_name,employee_id_no',
                'teacherAssignments.section:id,name',
            ])
            ->findOrFail($subjectId);
    }

    public function create(array $data, User $admin): Subject
    {
        return DB::transaction(function () use ($data, $admin) {
            $subject = Subject::query()->create([
                'grade_level_id' => $data['grade_level_id'],
                'strand_id' => $data['strand_id'],
                'code' => strtoupper($data['code']),
                'name' => $data['name'],
                'is_hidden' => false,
            ]);

            $this->syncTeacherAssignments($subject, $data['teacher_id']);

            $this->referenceDataService->clearCache();
            Cache::forget('admin.dashboard.stats');

            $this->activityLogService->log(
                user: $admin,
                actionType: 'create',
                moduleName: 'admin_subjects',
                description: "Created subject: {$subject->name}",
                newValues: ['subject_id' => $subject->id],
            );

            return $subject->load(['gradeLevel:id,name', 'strand:id,code,name']);
        });
    }

    public function update(Subject $subject, array $data, User $admin): Subject
    {
        return DB::transaction(function () use ($subject, $data, $admin) {
            $oldValues = $subject->only(['code', 'name', 'grade_level_id', 'strand_id', 'is_hidden']);

            $subject->update(array_filter([
                'grade_level_id' => $data['grade_level_id'] ?? null,
                'strand_id' => $data['strand_id'] ?? null,
                'code' => isset($data['code']) ? strtoupper($data['code']) : null,
                'name' => $data['name'] ?? null,
                'is_hidden' => $data['is_hidden'] ?? null,
            ], fn ($v) => $v !== null));

            if (! empty($data['teacher_id'])) {
                $this->syncTeacherAssignments($subject->fresh(), $data['teacher_id']);
            }

            $this->referenceDataService->clearCache();

            $this->activityLogService->log(
                user: $admin,
                actionType: 'update',
                moduleName: 'admin_subjects',
                description: "Updated subject: {$subject->name}",
                oldValues: $oldValues,
                newValues: $subject->fresh()->only(['code', 'name', 'is_hidden']),
            );

            return $subject->fresh()->load(['gradeLevel:id,name', 'strand:id,code,name']);
        });
    }

    public function delete(Subject $subject, User $admin): void
    {
        DB::transaction(function () use ($subject, $admin) {
            $subject->delete();

            $this->referenceDataService->clearCache();
            Cache::forget('admin.dashboard.stats');

            $this->activityLogService->log(
                user: $admin,
                actionType: 'delete',
                moduleName: 'admin_subjects',
                description: "Soft-deleted subject: {$subject->name}",
                oldValues: ['subject_id' => $subject->id],
            );
        });
    }

    public function bulkAction(array $subjectIds, string $action, User $admin): int
    {
        return DB::transaction(function () use ($subjectIds, $action, $admin) {
            $subjects = Subject::query()->whereIn('id', $subjectIds)->get();
            $count = $subjects->count();

            if ($count === 0) {
                return 0;
            }

            match ($action) {
                'hide' => Subject::query()->whereIn('id', $subjectIds)->update(['is_hidden' => true]),
                'unhide' => Subject::query()->whereIn('id', $subjectIds)->update(['is_hidden' => false]),
                'delete' => $subjects->each->delete(),
                default => abort(422, 'Invalid bulk action.'),
            };

            $this->referenceDataService->clearCache();
            Cache::forget('admin.dashboard.stats');

            $this->activityLogService->log(
                user: $admin,
                actionType: $action === 'delete' ? 'delete' : 'update',
                moduleName: 'admin_subjects',
                description: "Bulk {$action} on {$count} subject(s).",
                newValues: ['subject_ids' => $subjectIds, 'action' => $action],
            );

            return $count;
        });
    }

    private function syncTeacherAssignments(Subject $subject, string $teacherId): void
    {
        $schoolYear = SchoolYear::query()->where('is_current', true)->first();
        $semester = Semester::query()->where('is_active', true)->first();

        if ($schoolYear === null || $semester === null) {
            return;
        }

        $sections = Section::query()
            ->select('id')
            ->where('grade_level_id', $subject->grade_level_id)
            ->where('strand_id', $subject->strand_id)
            ->where('school_year_id', $schoolYear->id)
            ->get();

        foreach ($sections as $section) {
            SubjectTeacherAssignment::query()->updateOrCreate(
                [
                    'subject_id' => $subject->id,
                    'school_year_id' => $schoolYear->id,
                    'semester_id' => $semester->id,
                    'section_id' => $section->id,
                ],
                ['teacher_id' => $teacherId]
            );
        }
    }
}
