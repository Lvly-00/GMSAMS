<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSubjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'string', 'max:150'],
            'code' => ['sometimes', 'string', 'max:20'],
            'grade_level_id' => ['sometimes', 'exists:grade_levels,id'],
            'strand_id' => ['sometimes', 'exists:strands,id'],
            'teacher_id' => ['sometimes', 'exists:teachers,id'],
            'is_hidden' => ['sometimes', 'boolean'],
        ];
    }
}
