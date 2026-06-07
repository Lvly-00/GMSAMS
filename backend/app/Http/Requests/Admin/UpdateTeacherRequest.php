<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTeacherRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId = $this->route('user');
        $teacherId = optional(\App\Models\User::find($userId)?->teacher)->id;

        return [
            'employee_id_no' => ['sometimes', 'string', 'max:30', Rule::unique('teachers', 'employee_id_no')->ignore($teacherId)->whereNull('deleted_at')],
            'first_name' => ['sometimes', 'string', 'max:100'],
            'last_name' => ['sometimes', 'string', 'max:100'],
            'username' => ['sometimes', 'string', 'max:50', Rule::unique('users', 'username')->ignore($userId)->whereNull('deleted_at')],
            'email' => ['sometimes', 'email', 'max:255', Rule::unique('users', 'email')->ignore($userId)->whereNull('deleted_at')],
            'password' => ['sometimes', 'string', 'min:6', 'max:18', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[^\s]{6,18}$/'],
            'department' => ['nullable', 'string', 'max:100'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }
}
