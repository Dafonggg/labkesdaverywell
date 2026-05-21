<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Qc\ApproveQcRequest;
use App\Http\Requests\Qc\RejectQcRequest;
use App\Http\Resources\VerifikasiQcResource;
use App\Services\ActivityLogService;
use App\Services\QcService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class QcController extends Controller
{
    use ApiResponseTrait;

    public function __construct(
        protected QcService $qcService,
        protected ActivityLogService $activityLogService,
    ) {}

    /**
     * GET /qc/history
     */
    public function history(Request $request): JsonResponse
    {
        $filters = $request->only(['per_page', 'page']);
        $history = $this->qcService->getHistory($filters);

        return $this->successResponse(
            VerifikasiQcResource::collection($history),
            'Riwayat verifikasi QC berhasil diambil',
            200,
            [
                'current_page' => $history->currentPage(),
                'last_page' => $history->lastPage(),
                'per_page' => $history->perPage(),
                'total' => $history->total(),
            ]
        );
    }

    /**
     * POST /qc/approve
     */
    public function approve(ApproveQcRequest $request): JsonResponse
    {
        $verifikasi = $this->qcService->approve($request->validated());

        $this->activityLogService->log(
            'qc_approve',
            'VerifikasiQc',
            $verifikasi->id,
            null,
            $verifikasi->toArray()
        );

        return $this->successResponse(
            new VerifikasiQcResource($verifikasi),
            'Hasil uji berhasil disetujui'
        );
    }

    /**
     * POST /qc/reject
     */
    public function reject(RejectQcRequest $request): JsonResponse
    {
        $verifikasi = $this->qcService->reject($request->validated());

        $this->activityLogService->log(
            'qc_reject',
            'VerifikasiQc',
            $verifikasi->id,
            null,
            $verifikasi->toArray()
        );

        return $this->successResponse(
            new VerifikasiQcResource($verifikasi),
            'Hasil uji berhasil ditolak'
        );
    }
}
