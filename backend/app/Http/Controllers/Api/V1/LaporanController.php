<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Laporan\GenerateLaporanRequest;
use App\Http\Resources\DraftLaporanResource;
use App\Http\Resources\LaporanFinalResource;
use App\Services\ActivityLogService;
use App\Services\LaporanService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    use ApiResponseTrait;

    public function __construct(
        protected LaporanService $laporanService,
        protected ActivityLogService $activityLogService,
    ) {}

    /**
     * POST /laporan/generate
     */
    public function generate(GenerateLaporanRequest $request): JsonResponse
    {
        $draft = $this->laporanService->generateDraft($request->validated());

        $this->activityLogService->log(
            'generate',
            'DraftLaporan',
            $draft->id,
            null,
            $draft->toArray()
        );

        return $this->createdResponse(
            new DraftLaporanResource($draft),
            'Draft laporan berhasil dibuat'
        );
    }

    /**
     * GET /laporan/draft
     */
    public function drafts(): JsonResponse
    {
        $drafts = $this->laporanService->getDrafts();

        return $this->successResponse(
            DraftLaporanResource::collection($drafts),
            'Data draft laporan berhasil diambil',
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
     * GET /laporan/{id}/download
     */
    public function download(string $id): JsonResponse
    {
        $draft = $this->laporanService->getById($id);

        // PDF generation will be implemented in a future phase
        // For now, return the draft data
        return $this->successResponse(
            new DraftLaporanResource($draft),
            'Data laporan berhasil diambil (PDF generation coming soon)'
        );
    }

    /**
     * GET /laporan/final
     */
    public function finalReports(Request $request): JsonResponse
    {
        $filters = $request->only(['per_page', 'page']);
        $laporanFinal = $this->laporanService->getLaporanFinal($filters);

        return $this->successResponse(
            LaporanFinalResource::collection($laporanFinal),
            'Data laporan final berhasil diambil',
            200,
            [
                'current_page' => $laporanFinal->currentPage(),
                'last_page' => $laporanFinal->lastPage(),
                'per_page' => $laporanFinal->perPage(),
                'total' => $laporanFinal->total(),
            ]
        );
    }
}
