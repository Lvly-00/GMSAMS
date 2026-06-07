<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\ResendOtpRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(
        private readonly AuthService $authService,
    ) {}

    public function login(LoginRequest $request): JsonResponse
    {
        $result = $this->authService->attemptLogin(
            login: $request->validated('login'),
            password: $request->validated('password'),
            remember: $request->boolean('remember'),
            request: $request,
        );

        $redirectUrl = $this->dashboardRouteForRole($result['user']->role->name);

        return response()->json([
            'message' => 'Login successful.',
            'token' => $result['token'],
            'user' => new UserResource($result['user']),
            'redirect_to' => $redirectUrl, // <--- Add this
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $user = $request->user();
        $plainToken = $request->bearerToken();

        $this->authService->logout($user, $request, $plainToken);

        return response()->json([
            'message' => 'Logged out successfully.',
        ]);
    }

    public function me(Request $request): UserResource
    {
        $user = $request->user()->load(['role:id,name', 'student:id,user_id,first_name,last_name,student_id_no', 'teacher:id,user_id,first_name,last_name,employee_id_no,is_head_teacher']);

        return new UserResource($user);
    }

    public function forgotPassword(ForgotPasswordRequest $request): JsonResponse
    {
        $this->authService->sendPasswordResetOtp(
            login: $request->validated('login'),
            request: $request,
        );

        return response()->json([
            'message' => 'If an account exists, an OTP has been sent to the registered email.',
        ]);
    }

    public function resetPassword(ResetPasswordRequest $request): JsonResponse
    {
        $user = User::query()
            ->where('username', $request->validated('login'))
            ->orWhere('email', $request->validated('login'))
            ->firstOrFail();

        $this->authService->resetPassword(
            user: $user,
            otp: $request->validated('otp'),
            password: $request->validated('password'),
            request: $request,
        );

        return response()->json([
            'message' => 'Password reset successful. You may now log in.',
        ]);
    }

    public function resendOtp(ResendOtpRequest $request): JsonResponse
    {
        $user = User::query()
            ->where('username', $request->validated('login'))
            ->orWhere('email', $request->validated('login'))
            ->firstOrFail();

        $this->authService->sendOtp(
            login: $request->validated('login'),
            purpose: $request->validated('purpose'),
            request: $request,
        );

        return response()->json([
            'message' => 'OTP sent successfully.',
        ]);
    }

    private function dashboardRouteForRole(string $roleName): string
    {
        return match ($roleName) {
            'admin' => '/admin/dashboard',
            'head_teacher' => '/head-teacher/dashboard',
            'teacher' => '/teacher/dashboard',
            'student' => '/student/dashboard',
            default => '/login',
        };
    }
}
