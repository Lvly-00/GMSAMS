<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\ReferenceDataService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminReferenceController extends Controller
{
    public function __construct(
        private readonly ReferenceDataService $referenceService,
    ) {}

    public function index(Request $request): JsonResponse
    {
        return response()->json([
            'grade_levels' => $this->referenceService->gradeLevels(),
            'strands' => $this->referenceService->strands(),
            'school_years' => $this->referenceService->schoolYears(),
            'semesters' => $this->referenceService->semesters($request->integer('school_year_id') ?: null),
            'sections' => $this->referenceService->sections(
                $request->integer('school_year_id') ?: null,
                $request->integer('grade_level_id') ?: null,
                $request->integer('strand_id') ?: null,
            ),
            'teachers' => $this->referenceService->teachers(),
        ]);
    }
}
