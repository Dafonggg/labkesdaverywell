<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\HasilUji\StoreHasilUjiRequest;
use App\Http\Resources\HasilUjiResource;
use App\Services\ActivityLogService;
use App\Services\HasilUjiService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;

class HasilUjiController extends Controller
{
    use ApiResponseTrait;

    public function __construct(
        protected HasilUjiService $hasilUjiService,
        protected ActivityLogService $activityLogService,
    ) {}

    /**
     * POST /hasil-uji
     */
    public function store(StoreHasilUjiRequest $request): JsonResponse
    {
        try {
            $hasilUji = $this->hasilUjiService->create($request->validated());

            $this->activityLogService->log(
                'create',
                'HasilUji',
                $hasilUji->id,
                null,
                $hasilUji->toArray()
            );

            return $this->createdResponse(
                new HasilUjiResource($hasilUji),
                'Hasil uji berhasil disimpan'
            );
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    /**
     * GET /hasil-uji/pending-qc
     */
    public function pendingQc(): JsonResponse
    {
        $hasilUji = $this->hasilUjiService->getPendingQc();

        return $this->successResponse(
            HasilUjiResource::collection($hasilUji),
            'Data hasil uji pending QC berhasil diambil',
            200,
            [
                'current_page' => $hasilUji->currentPage(),
                'last_page' => $hasilUji->lastPage(),
                'per_page' => $hasilUji->perPage(),
                'total' => $hasilUji->total(),
            ]
        );
    }
}
