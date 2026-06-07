<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTeacherRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'employee_id_no' => ['required', 'string', 'max:30', Rule::unique('teachers', 'employee_id_no')->whereNull('deleted_at')],
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'username' => ['required', 'string', 'max:50', Rule::unique('users', 'username')->whereNull('deleted_at')],
            'email' => ['nullable', 'email', 'max:255', Rule::unique('users', 'email')->whereNull('deleted_at')],
            'password' => ['required', 'string', 'min:6', 'max:18', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[^\s]{6,18}$/'],
            'department' => ['nullable', 'string', 'max:100'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }
}
