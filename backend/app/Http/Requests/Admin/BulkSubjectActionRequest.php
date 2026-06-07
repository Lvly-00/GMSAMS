<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BulkSubjectActionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'subject_ids' => ['required', 'array', 'min:1'],
            'subject_ids.*' => ['uuid', 'exists:subjects,id'],
            'action' => ['required', Rule::in(['hide', 'unhide', 'delete'])],
        ];
    }
}
