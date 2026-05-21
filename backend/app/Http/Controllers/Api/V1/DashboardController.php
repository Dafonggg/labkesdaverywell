<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\DashboardService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;

class DashboardController extends Controller
{
    use ApiResponseTrait;

    public function __construct(
        protected DashboardService $dashboardService,
    ) {}

    /**
     * GET /dashboard/summary
     */
    public function summary(): JsonResponse
    {
        $summary = $this->dashboardService->getSummary();

        return $this->successResponse($summary, 'Dashboard summary berhasil diambil');
    }
}
