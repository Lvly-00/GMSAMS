<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreStudentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:100'],
            'middle_name' => ['nullable', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'suffix' => ['nullable', 'string', 'max:20'],
            'gender' => ['required', Rule::in(['Male', 'Female'])],
            'birthdate' => ['required', 'date', 'before:today'],
            'lrn' => ['required', 'digits:12', Rule::unique('students', 'lrn')->whereNull('deleted_at')],
            'student_id_no' => ['nullable', 'string', 'max:30', Rule::unique('students', 'student_id_no')->whereNull('deleted_at')],
            'username' => ['required', 'string', 'max:50', Rule::unique('users', 'username')->whereNull('deleted_at')],
            'email' => ['nullable', 'email', 'max:255', Rule::unique('users', 'email')->whereNull('deleted_at')],
            'password' => ['required', 'string', 'min:6', 'max:18', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[^\s]{6,18}$/'],
            'school_year_id' => ['required', 'exists:school_years,id'],
            'semester_id' => ['required', 'exists:semesters,id'],
            'grade_level_id' => ['required', 'exists:grade_levels,id'],
            'strand_id' => ['required', 'exists:strands,id'],
            'section_id' => ['required', 'exists:sections,id'],
            'adviser_id' => ['nullable', 'exists:teachers,id'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }
}
