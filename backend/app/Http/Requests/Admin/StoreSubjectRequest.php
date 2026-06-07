<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSubjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:150'],
            'code' => ['required', 'string', 'max:20'],
            'grade_level_id' => ['required', 'exists:grade_levels,id'],
            'strand_id' => ['required', 'exists:strands,id'],
            'teacher_id' => ['required', 'exists:teachers,id'],
        ];
    }
}
