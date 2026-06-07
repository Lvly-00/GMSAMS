<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreStudentRequest;
use App\Http\Requests\Admin\StoreTeacherRequest;
use App\Http\Requests\Admin\UpdateStudentRequest;
use App\Http\Requests\Admin\UpdateTeacherRequest;
use App\Http\Resources\Admin\AdminUserResource;
use App\Models\User;
use App\Services\Admin\AdminAccountService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class AdminUserController extends Controller
{
    public function __construct(
        private readonly AdminAccountService $accountService,
    ) {}

    public function index(Request $request): AnonymousResourceCollection
    {
        $users = $this->accountService->list(
            filters: $request->only(['role', 'is_active', 'search']),
            perPage: (int) $request->get('per_page', 15),
        );

        return AdminUserResource::collection($users);
    }

    public function show(string $user): AdminUserResource
    {
        return new AdminUserResource($this->accountService->find($user));
    }

    public function storeStudent(StoreStudentRequest $request): JsonResponse
    {
        $user = $this->accountService->createStudent($request->validated(), $request->user());

        return response()->json([
            'message' => 'Student account created successfully.',
            'data' => new AdminUserResource($user),
        ], 201);
    }

    public function storeTeacher(StoreTeacherRequest $request): JsonResponse
    {
        $user = $this->accountService->createTeacher($request->validated(), $request->user(), false);

        return response()->json([
            'message' => 'Teacher account created successfully.',
            'data' => new AdminUserResource($user),
        ], 201);
    }

    public function storeHeadTeacher(StoreTeacherRequest $request): JsonResponse
    {
        $user = $this->accountService->createTeacher($request->validated(), $request->user(), true);

        return response()->json([
            'message' => 'Head teacher account created successfully.',
            'data' => new AdminUserResource($user),
        ], 201);
    }

    public function updateStudent(UpdateStudentRequest $request, string $user): JsonResponse
    {
        $model = User::query()->findOrFail($user);
        $updated = $this->accountService->updateStudent($model, $request->validated(), $request->user());

        return response()->json([
            'message' => 'Student account updated successfully.',
            'data' => new AdminUserResource($updated),
        ]);
    }

    public function updateTeacher(UpdateTeacherRequest $request, string $user): JsonResponse
    {
        $model = User::query()->findOrFail($user);
        $updated = $this->accountService->updateTeacher($model, $request->validated(), $request->user());

        return response()->json([
            'message' => 'Teacher account updated successfully.',
            'data' => new AdminUserResource($updated),
        ]);
    }

    public function destroy(Request $request, string $user): JsonResponse
    {
        $model = User::query()->findOrFail($user);
        $this->accountService->delete($model, $request->user());

        return response()->json([
            'message' => 'Account deleted successfully.',
        ]);
    }
}
