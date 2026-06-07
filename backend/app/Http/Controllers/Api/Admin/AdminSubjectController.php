<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\BulkSubjectActionRequest;
use App\Http\Requests\Admin\StoreSubjectRequest;
use App\Http\Requests\Admin\UpdateSubjectRequest;
use App\Http\Resources\Admin\SubjectResource;
use App\Models\Subject;
use App\Services\Admin\AdminSubjectService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class AdminSubjectController extends Controller
{
    public function __construct(
        private readonly AdminSubjectService $subjectService,
    ) {}

    public function index(Request $request): AnonymousResourceCollection
    {
        $subjects = $this->subjectService->list(
            filters: $request->only(['grade_level_id', 'strand_id', 'is_hidden', 'search', 'include_hidden']),
            perPage: (int) $request->get('per_page', 15),
        );

        return SubjectResource::collection($subjects);
    }

    public function show(string $subject): SubjectResource
    {
        return new SubjectResource($this->subjectService->find($subject));
    }

    public function store(StoreSubjectRequest $request): JsonResponse
    {
        $subject = $this->subjectService->create($request->validated(), $request->user());

        return response()->json([
            'message' => 'Subject created successfully.',
            'data' => new SubjectResource($subject),
        ], 201);
    }

    public function update(UpdateSubjectRequest $request, string $subject): JsonResponse
    {
        $model = Subject::query()->findOrFail($subject);
        $updated = $this->subjectService->update($model, $request->validated(), $request->user());

        return response()->json([
            'message' => 'Subject updated successfully.',
            'data' => new SubjectResource($updated),
        ]);
    }

    public function destroy(Request $request, string $subject): JsonResponse
    {
        $model = Subject::query()->findOrFail($subject);
        $this->subjectService->delete($model, $request->user());

        return response()->json([
            'message' => 'Subject deleted successfully.',
        ]);
    }

    public function bulk(BulkSubjectActionRequest $request): JsonResponse
    {
        $count = $this->subjectService->bulkAction(
            $request->validated('subject_ids'),
            $request->validated('action'),
            $request->user(),
        );

        return response()->json([
            'message' => "Bulk action applied to {$count} subject(s).",
            'affected' => $count,
        ]);
    }
}
