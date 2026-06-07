<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\ActivityLogResource;
use App\Services\Admin\AdminDashboardService;
use Illuminate\Http\JsonResponse;

class AdminDashboardController extends Controller
{
    public function __construct(
        private readonly AdminDashboardService $dashboardService,
    ) {}

    public function index(): JsonResponse
    {
        $stats = $this->dashboardService->getStats();
        $activityLogs = $this->dashboardService->getActivityFeed();

        return response()->json([
            'stats' => $stats,
            'activity_logs' => ActivityLogResource::collection($activityLogs),
            'activity_logs_meta' => [
                'current_page' => $activityLogs->currentPage(),
                'last_page' => $activityLogs->lastPage(),
                'per_page' => $activityLogs->perPage(),
                'total' => $activityLogs->total(),
            ],
        ]);
    }
}
