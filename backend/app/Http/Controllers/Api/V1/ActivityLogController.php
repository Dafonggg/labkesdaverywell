<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\ActivityLogResource;
use App\Services\ActivityLogService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    use ApiResponseTrait;

    public function __construct(
        protected ActivityLogService $activityLogService,
    ) {}

    /**
     * GET /activity-logs
     */
    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(['user_id', 'action', 'entity_type', 'per_page']);
        $logs = $this->activityLogService->getAll($filters);

        return $this->successResponse(
            ActivityLogResource::collection($logs),
            'Activity logs berhasil diambil',
            200,
            [
                'current_page' => $logs->currentPage(),
                'last_page' => $logs->lastPage(),
                'per_page' => $logs->perPage(),
                'total' => $logs->total(),
            ]
        );
    }
}
