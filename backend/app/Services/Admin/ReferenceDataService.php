<?php

namespace App\Services\Admin;

use App\Models\GradeLevel;
use App\Models\Role;
use App\Models\SchoolYear;
use App\Models\Section;
use App\Models\Semester;
use App\Models\Strand;
use App\Models\Teacher;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class ReferenceDataService
{
    public function gradeLevels(): Collection
    {
        return Cache::remember('ref.grade_levels', 3600, fn () => GradeLevel::query()
            ->select('id', 'name')
            ->orderBy('name')
            ->get());
    }

    public function strands(): Collection
    {
        return Cache::remember('ref.strands', 3600, fn () => Strand::query()
            ->select('id', 'code', 'name')
            ->orderBy('code')
            ->get());
    }

    public function schoolYears(): Collection
    {
        return Cache::remember('ref.school_years', 3600, fn () => SchoolYear::query()
            ->select('id', 'label', 'is_current')
            ->orderByDesc('label')
            ->get());
    }

    public function semesters(?int $schoolYearId = null): Collection
    {
        $key = 'ref.semesters.'.($schoolYearId ?? 'all');

        return Cache::remember($key, 3600, function () use ($schoolYearId) {
            $query = Semester::query()
                ->select('id', 'school_year_id', 'name', 'is_active', 'start_date', 'end_date');

            if ($schoolYearId !== null) {
                $query->where('school_year_id', $schoolYearId);
            }

            return $query->orderBy('name')->get();
        });
    }

    public function sections(?int $schoolYearId = null, ?int $gradeLevelId = null, ?int $strandId = null): Collection
    {
        return Section::query()
            ->select('id', 'school_year_id', 'grade_level_id', 'strand_id', 'name', 'adviser_id')
            ->with([
                'gradeLevel:id,name',
                'strand:id,code,name',
                'adviser:id,first_name,last_name',
            ])
            ->when($schoolYearId, fn ($q) => $q->where('school_year_id', $schoolYearId))
            ->when($gradeLevelId, fn ($q) => $q->where('grade_level_id', $gradeLevelId))
            ->when($strandId, fn ($q) => $q->where('strand_id', $strandId))
            ->orderBy('name')
            ->get();
    }

    public function teachers(bool $headTeachersOnly = false): Collection
    {
        return Teacher::query()
            ->select('id', 'user_id', 'employee_id_no', 'first_name', 'last_name', 'is_head_teacher', 'department')
            ->with('user:id,username,email,is_active')
            ->when($headTeachersOnly, fn ($q) => $q->where('is_head_teacher', true))
            ->whereNull('deleted_at')
            ->orderBy('last_name')
            ->get();
    }

    public function roles(): Collection
    {
        return Cache::remember('ref.roles', 3600, fn () => Role::query()
            ->select('id', 'name', 'description')
            ->whereIn('name', ['student', 'teacher', 'head_teacher'])
            ->orderBy('name')
            ->get());
    }

    public function clearCache(): void
    {
        Cache::forget('ref.grade_levels');
        Cache::forget('ref.strands');
        Cache::forget('ref.school_years');
        Cache::forget('admin.dashboard.stats');

        foreach (SchoolYear::query()->pluck('id') as $id) {
            Cache::forget("ref.semesters.{$id}");
        }
        Cache::forget('ref.semesters.all');
    }
}
