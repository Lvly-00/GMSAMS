<?php

namespace App\Services\Admin;

use App\Models\EnrollmentRecord;
use App\Models\Role;
use App\Models\Section;
use App\Models\Semester;
use App\Models\Student;
use App\Models\Subject;
use App\Models\SubjectTeacherAssignment;
use App\Models\Teacher;
use App\Models\User;
use App\Services\ActivityLogService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class AdminAccountService
{
    public function __construct(
        private readonly ActivityLogService $activityLogService,
        private readonly ReferenceDataService $referenceDataService,
    ) {}

    public function list(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = User::query()
            ->select('id', 'role_id', 'username', 'email', 'is_active', 'last_login_at', 'created_at')
            ->with([
                'role:id,name',
                'student:id,user_id,student_id_no,first_name,middle_name,last_name,lrn',
                'teacher:id,user_id,employee_id_no,first_name,last_name,is_head_teacher,department',
            ])
            ->whereHas('role', fn ($q) => $q->whereIn('name', ['student', 'teacher', 'head_teacher']));

        if (! empty($filters['role'])) {
            $query->whereHas('role', fn ($q) => $q->where('name', $filters['role']));
        }

        if (isset($filters['is_active'])) {
            $query->where('is_active', filter_var($filters['is_active'], FILTER_VALIDATE_BOOLEAN));
        }

        if (! empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('username', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhereHas('student', function ($sq) use ($search) {
                        $sq->where('first_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%")
                            ->orWhere('student_id_no', 'like', "%{$search}%")
                            ->orWhere('lrn', 'like', "%{$search}%");
                    })
                    ->orWhereHas('teacher', function ($tq) use ($search) {
                        $tq->where('first_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%")
                            ->orWhere('employee_id_no', 'like', "%{$search}%");
                    });
            });
        }

        return $query->orderByDesc('created_at')->paginate($perPage);
    }

    public function find(string $userId): User
    {
        return User::query()
            ->with([
                'role:id,name',
                'student.enrollmentRecords.section:id,name',
                'student.enrollmentRecords.gradeLevel:id,name',
                'student.enrollmentRecords.strand:id,code,name',
                'student.enrollmentRecords.schoolYear:id,label',
                'student.enrollmentRecords.semester:id,name',
                'teacher',
            ])
            ->findOrFail($userId);
    }

    public function createStudent(array $data, User $admin): User
    {
        $studentRole = Role::query()->where('name', 'student')->firstOrFail();

        return DB::transaction(function () use ($data, $admin, $studentRole) {
            $email = $data['email'] ?? $this->defaultEmail($data['username']);

            $user = User::query()->create([
                'role_id' => $studentRole->id,
                'username' => $data['username'],
                'email' => $email,
                'password_hash' => $data['password'],
                'is_active' => $data['is_active'] ?? true,
                'email_verified' => true,
            ]);

            $studentIdNo = $data['student_id_no'] ?? $this->generateStudentIdNo();

            $student = Student::query()->create([
                'user_id' => $user->id,
                'student_id_no' => $studentIdNo,
                'lrn' => $data['lrn'],
                'first_name' => $data['first_name'],
                'middle_name' => $data['middle_name'] ?? null,
                'last_name' => $data['last_name'],
                'suffix' => $data['suffix'] ?? null,
                'gender' => $data['gender'],
                'birthdate' => $data['birthdate'],
            ]);

            EnrollmentRecord::query()->create([
                'student_id' => $student->id,
                'school_year_id' => $data['school_year_id'],
                'semester_id' => $data['semester_id'],
                'grade_level_id' => $data['grade_level_id'],
                'strand_id' => $data['strand_id'],
                'section_id' => $data['section_id'],
                'status' => 'active',
            ]);

            if (! empty($data['adviser_id'])) {
                Section::query()
                    ->where('id', $data['section_id'])
                    ->update(['adviser_id' => $data['adviser_id']]);
            }

            $this->syncSubjectAssignmentsForEnrollment($data);

            $this->referenceDataService->clearCache();
            Cache::forget('admin.dashboard.stats');

            $this->activityLogService->log(
                user: $admin,
                actionType: 'create',
                moduleName: 'admin_accounts',
                description: "Created student account: {$user->username}",
                newValues: ['user_id' => $user->id, 'student_id' => $student->id],
            );

            return $user->load(['role:id,name', 'student']);
        });
    }

    public function createTeacher(array $data, User $admin, bool $asHeadTeacher = false): User
    {
        $roleName = $asHeadTeacher ? 'head_teacher' : 'teacher';
        $role = Role::query()->where('name', $roleName)->firstOrFail();

        return DB::transaction(function () use ($data, $admin, $role, $asHeadTeacher) {
            $email = $data['email'] ?? $this->defaultEmail($data['username']);

            $user = User::query()->create([
                'role_id' => $role->id,
                'username' => $data['username'],
                'email' => $email,
                'password_hash' => $data['password'],
                'is_active' => $data['is_active'] ?? true,
                'email_verified' => true,
            ]);

            $teacher = Teacher::query()->create([
                'user_id' => $user->id,
                'employee_id_no' => $data['employee_id_no'],
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'is_head_teacher' => $asHeadTeacher,
                'department' => $data['department'] ?? 'Senior High School',
            ]);

            Cache::forget('admin.dashboard.stats');

            $this->activityLogService->log(
                user: $admin,
                actionType: 'create',
                moduleName: 'admin_accounts',
                description: "Created {$role->name} account: {$user->username}",
                newValues: ['user_id' => $user->id, 'teacher_id' => $teacher->id],
            );

            return $user->load(['role:id,name', 'teacher']);
        });
    }

    public function updateStudent(User $user, array $data, User $admin): User
    {
        if (! $user->hasRole('student')) {
            throw ValidationException::withMessages(['user' => ['User is not a student account.']]);
        }

        return DB::transaction(function () use ($user, $data, $admin) {
            $oldValues = $user->only(['username', 'email', 'is_active']);

            $user->update(array_filter([
                'username' => $data['username'] ?? null,
                'email' => $data['email'] ?? null,
                'is_active' => $data['is_active'] ?? null,
            ], fn ($v) => $v !== null));

            if (! empty($data['password'])) {
                $user->update(['password_hash' => $data['password']]);
            }

            $user->student->update(array_filter([
                'first_name' => $data['first_name'] ?? null,
                'middle_name' => $data['middle_name'] ?? null,
                'last_name' => $data['last_name'] ?? null,
                'suffix' => $data['suffix'] ?? null,
                'lrn' => $data['lrn'] ?? null,
                'gender' => $data['gender'] ?? null,
                'birthdate' => $data['birthdate'] ?? null,
                'student_id_no' => $data['student_id_no'] ?? null,
            ], fn ($v) => $v !== null));

            if ($this->hasEnrollmentUpdate($data)) {
                $enrollment = $user->student->enrollmentRecords()->latest('id')->first();

                if ($enrollment !== null) {
                    $enrollment->update(array_filter([
                        'school_year_id' => $data['school_year_id'] ?? null,
                        'semester_id' => $data['semester_id'] ?? null,
                        'grade_level_id' => $data['grade_level_id'] ?? null,
                        'strand_id' => $data['strand_id'] ?? null,
                        'section_id' => $data['section_id'] ?? null,
                    ], fn ($v) => $v !== null));

                    if (! empty($data['adviser_id']) && ! empty($data['section_id'])) {
                        Section::query()
                            ->where('id', $data['section_id'])
                            ->update(['adviser_id' => $data['adviser_id']]);
                    }

                    $this->syncSubjectAssignmentsForEnrollment(array_merge($enrollment->toArray(), $data));
                }
            }

            Cache::forget('admin.dashboard.stats');

            $this->activityLogService->log(
                user: $admin,
                actionType: 'update',
                moduleName: 'admin_accounts',
                description: "Updated student account: {$user->username}",
                oldValues: $oldValues,
                newValues: $user->fresh()->only(['username', 'email', 'is_active']),
            );

            return $user->fresh()->load(['role:id,name', 'student.enrollmentRecords']);
        });
    }

    public function updateTeacher(User $user, array $data, User $admin): User
    {
        if (! $user->hasRole('teacher') && ! $user->hasRole('head_teacher')) {
            throw ValidationException::withMessages(['user' => ['User is not a teacher account.']]);
        }

        return DB::transaction(function () use ($user, $data, $admin) {
            $oldValues = $user->only(['username', 'email', 'is_active']);

            $user->update(array_filter([
                'username' => $data['username'] ?? null,
                'email' => $data['email'] ?? null,
                'is_active' => $data['is_active'] ?? null,
            ], fn ($v) => $v !== null));

            if (! empty($data['password'])) {
                $user->update(['password_hash' => $data['password']]);
            }

            $user->teacher->update(array_filter([
                'employee_id_no' => $data['employee_id_no'] ?? null,
                'first_name' => $data['first_name'] ?? null,
                'last_name' => $data['last_name'] ?? null,
                'department' => $data['department'] ?? null,
            ], fn ($v) => $v !== null));

            $this->activityLogService->log(
                user: $admin,
                actionType: 'update',
                moduleName: 'admin_accounts',
                description: "Updated teacher account: {$user->username}",
                oldValues: $oldValues,
                newValues: $user->fresh()->only(['username', 'email', 'is_active']),
            );

            return $user->fresh()->load(['role:id,name', 'teacher']);
        });
    }

    public function delete(User $user, User $admin): void
    {
        if ($user->hasRole('admin')) {
            throw ValidationException::withMessages(['user' => ['Admin accounts cannot be deleted through this endpoint.']]);
        }

        DB::transaction(function () use ($user, $admin) {
            if ($user->student !== null) {
                $user->student->delete();
            }

            if ($user->teacher !== null) {
                $user->teacher->delete();
            }

            $user->tokens()->delete();
            $user->delete();

            Cache::forget('admin.dashboard.stats');

            $this->activityLogService->log(
                user: $admin,
                actionType: 'delete',
                moduleName: 'admin_accounts',
                description: "Soft-deleted account: {$user->username}",
                oldValues: ['user_id' => $user->id],
            );
        });
    }

    private function syncSubjectAssignmentsForEnrollment(array $data): void
    {
        $subjects = Subject::query()
            ->select('id')
            ->where('grade_level_id', $data['grade_level_id'])
            ->where('strand_id', $data['strand_id'])
            ->where('is_hidden', false)
            ->whereNull('deleted_at')
            ->get();

        foreach ($subjects as $subject) {
            $existingAssignment = SubjectTeacherAssignment::query()
                ->where('subject_id', $subject->id)
                ->where('section_id', $data['section_id'])
                ->where('school_year_id', $data['school_year_id'])
                ->where('semester_id', $data['semester_id'])
                ->first();

            if ($existingAssignment !== null) {
                continue;
            }

            $teacherId = SubjectTeacherAssignment::query()
                ->where('subject_id', $subject->id)
                ->where('school_year_id', $data['school_year_id'])
                ->where('semester_id', $data['semester_id'])
                ->value('teacher_id');

            if ($teacherId === null) {
                continue;
            }

            SubjectTeacherAssignment::query()->create([
                'subject_id' => $subject->id,
                'teacher_id' => $teacherId,
                'school_year_id' => $data['school_year_id'],
                'semester_id' => $data['semester_id'],
                'section_id' => $data['section_id'],
            ]);
        }
    }

    private function hasEnrollmentUpdate(array $data): bool
    {
        return isset($data['school_year_id'], $data['semester_id'], $data['grade_level_id'], $data['strand_id'], $data['section_id'])
            || isset($data['section_id']);
    }

    private function defaultEmail(string $username): string
    {
        return strtolower($username).'@atec-apalit.edu.ph';
    }

    private function generateStudentIdNo(): string
    {
        $year = date('Y');
        $sequence = str_pad((string) (Student::withTrashed()->count() + 1), 4, '0', STR_PAD_LEFT);

        return "STU-{$year}-{$sequence}";
    }
}
