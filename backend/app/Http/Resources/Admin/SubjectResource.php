<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubjectResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'name' => $this->name,
            'is_hidden' => $this->is_hidden,
            'created_at' => $this->created_at?->toIso8601String(),
            'grade_level' => $this->whenLoaded('gradeLevel', fn () => $this->gradeLevel?->only(['id', 'name'])),
            'strand' => $this->whenLoaded('strand', fn () => $this->strand?->only(['id', 'code', 'name'])),
            'assignments' => $this->whenLoaded('teacherAssignments', fn () => $this->teacherAssignments->map(fn ($a) => [
                'id' => $a->id,
                'teacher' => $a->relationLoaded('teacher') ? $a->teacher?->only(['id', 'first_name', 'last_name', 'employee_id_no']) : null,
                'section' => $a->relationLoaded('section') ? $a->section?->only(['id', 'name']) : null,
            ])),
        ];
    }
}
