<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Approval\ApproveRequest;
use App\Http\Resources\DraftLaporanResource;
use App\Http\Resources\PersetujuanLaporanResource;
use App\Services\ActivityLogService;
use App\Services\ApprovalService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ApprovalController extends Controller
{
    use ApiResponseTrait;

    public function __construct(
        protected ApprovalService $approvalService,
        protected ActivityLogService $activityLogService,
    ) {}

    /**
     * GET /approval/pending
     */
    public function pending(Request $request): JsonResponse
    {
        $filters = $request->only(['per_page', 'page']);
        $drafts = $this->approvalService->getPending($filters);

        return $this->successResponse(
            DraftLaporanResource::collection($drafts),
            'Data draft menunggu persetujuan berhasil diambil',
            200,
            [
                'current_page' => $drafts->currentPage(),
                'last_page' => $drafts->lastPage(),
                'per_page' => $drafts->perPage(),
                'total' => $drafts->total(),
            ]
        );
    }

    /**
     * POST /approval/final
     */
    public function approveFinal(ApproveRequest $request): JsonResponse
    {
        $persetujuan = $this->approvalService->approveFinal($request->validated());

        $this->activityLogService->log(
            'approve_final',
            'PersetujuanLaporan',
            $persetujuan->id,
            null,
            $persetujuan->toArray()
        );

        return $this->successResponse(
            new PersetujuanLaporanResource($persetujuan),
            'Laporan berhasil disetujui'
        );
    }

    /**
     * POST /approval/reject
     */
    public function rejectFinal(ApproveRequest $request): JsonResponse
    {
        $persetujuan = $this->approvalService->rejectFinal($request->validated());

        $this->activityLogService->log(
            'reject_final',
            'PersetujuanLaporan',
            $persetujuan->id,
            null,
            $persetujuan->toArray()
        );

        return $this->successResponse(
            new PersetujuanLaporanResource($persetujuan),
            'Laporan ditolak dan dikembalikan untuk revisi'
        );
    }
}
