<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'username' => $this->username,
            'email' => $this->email,
            'is_active' => $this->is_active,
            'email_verified' => $this->email_verified,
            'last_login_at' => $this->last_login_at?->toIso8601String(),
            'role' => $this->whenLoaded('role', fn () => [
                'id' => $this->role->id,
                'name' => $this->role->name,
            ]),
            'profile' => $this->resolveProfile(),
        ];
    }

    private function resolveProfile(): ?array
    {
        if ($this->relationLoaded('student') && $this->student !== null) {
            return [
                'type' => 'student',
                'first_name' => $this->student->first_name,
                'last_name' => $this->student->last_name,
                'student_id_no' => $this->student->student_id_no,
            ];
        }

        if ($this->relationLoaded('teacher') && $this->teacher !== null) {
            return [
                'type' => 'teacher',
                'first_name' => $this->teacher->first_name,
                'last_name' => $this->teacher->last_name,
                'employee_id_no' => $this->teacher->employee_id_no,
                'is_head_teacher' => $this->teacher->is_head_teacher,
            ];
        }

        return null;
    }
}
