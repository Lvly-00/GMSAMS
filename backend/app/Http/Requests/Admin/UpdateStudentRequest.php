<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateStudentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId = $this->route('user');
        $studentId = $this->route('user') ? optional(\App\Models\User::find($this->route('user'))?->student)->id : null;

        return [
            'first_name' => ['sometimes', 'string', 'max:100'],
            'middle_name' => ['nullable', 'string', 'max:100'],
            'last_name' => ['sometimes', 'string', 'max:100'],
            'suffix' => ['nullable', 'string', 'max:20'],
            'gender' => ['sometimes', Rule::in(['Male', 'Female'])],
            'birthdate' => ['sometimes', 'date', 'before:today'],
            'lrn' => ['sometimes', 'digits:12', Rule::unique('students', 'lrn')->ignore($studentId)->whereNull('deleted_at')],
            'student_id_no' => ['sometimes', 'string', 'max:30', Rule::unique('students', 'student_id_no')->ignore($studentId)->whereNull('deleted_at')],
            'username' => ['sometimes', 'string', 'max:50', Rule::unique('users', 'username')->ignore($userId)->whereNull('deleted_at')],
            'email' => ['sometimes', 'email', 'max:255', Rule::unique('users', 'email')->ignore($userId)->whereNull('deleted_at')],
            'password' => ['sometimes', 'string', 'min:6', 'max:18', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[^\s]{6,18}$/'],
            'school_year_id' => ['sometimes', 'exists:school_years,id'],
            'semester_id' => ['sometimes', 'exists:semesters,id'],
            'grade_level_id' => ['sometimes', 'exists:grade_levels,id'],
            'strand_id' => ['sometimes', 'exists:strands,id'],
            'section_id' => ['sometimes', 'exists:sections,id'],
            'adviser_id' => ['nullable', 'exists:teachers,id'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }
}
