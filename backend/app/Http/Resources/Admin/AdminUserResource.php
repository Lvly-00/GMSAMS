<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminUserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'username' => $this->username,
            'email' => $this->email,
            'is_active' => $this->is_active,
            'last_login_at' => $this->last_login_at?->toIso8601String(),
            'created_at' => $this->created_at?->toIso8601String(),
            'role' => $this->whenLoaded('role', fn () => [
                'id' => $this->role->id,
                'name' => $this->role->name,
            ]),
            'student' => $this->whenLoaded('student', fn () => [
                'id' => $this->student->id,
                'student_id_no' => $this->student->student_id_no,
                'lrn' => $this->student->lrn,
                'first_name' => $this->student->first_name,
                'middle_name' => $this->student->middle_name,
                'last_name' => $this->student->last_name,
                'suffix' => $this->student->suffix,
                'full_name' => $this->student->full_name,
                'gender' => $this->student->gender,
                'birthdate' => $this->student->birthdate?->format('Y-m-d'),
                'enrollment' => $this->when(
                    $this->student->relationLoaded('enrollmentRecords'),
                    fn () => $this->student->enrollmentRecords->map(fn ($e) => [
                        'id' => $e->id,
                        'status' => $e->status,
                        'school_year' => $e->relationLoaded('schoolYear') ? $e->schoolYear?->only(['id', 'label']) : null,
                        'semester' => $e->relationLoaded('semester') ? $e->semester?->only(['id', 'name']) : null,
                        'grade_level' => $e->relationLoaded('gradeLevel') ? $e->gradeLevel?->only(['id', 'name']) : null,
                        'strand' => $e->relationLoaded('strand') ? $e->strand?->only(['id', 'code', 'name']) : null,
                        'section' => $e->relationLoaded('section') ? $e->section?->only(['id', 'name']) : null,
                    ])
                ),
            ]),
            'teacher' => $this->whenLoaded('teacher', fn () => [
                'id' => $this->teacher->id,
                'employee_id_no' => $this->teacher->employee_id_no,
                'first_name' => $this->teacher->first_name,
                'last_name' => $this->teacher->last_name,
                'full_name' => $this->teacher->full_name,
                'is_head_teacher' => $this->teacher->is_head_teacher,
                'department' => $this->teacher->department,
            ]),
        ];
    }
}
