<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class ResetPasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'login' => ['required', 'string', 'max:255'],
            'otp' => ['required', 'string', 'size:6'],
            'password' => [
                'required',
                'string',
                'min:6',
                'max:18',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[^\s]{6,18}$/',
                'confirmed',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'password.regex' => 'Password must include at least one uppercase letter, one lowercase letter, and one number. No spaces allowed.',
        ];
    }
}
