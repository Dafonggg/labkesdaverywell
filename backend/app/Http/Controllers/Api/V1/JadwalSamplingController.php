<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\JadwalSampling\StoreJadwalRequest;
use App\Http\Resources\JadwalSamplingResource;
use App\Services\ActivityLogService;
use App\Services\JadwalSamplingService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class JadwalSamplingController extends Controller
{
    use ApiResponseTrait;

    public function __construct(
        protected JadwalSamplingService $jadwalSamplingService,
        protected ActivityLogService $activityLogService,
    ) {}

    /**
     * GET /jadwal-sampling
     */
    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(['per_page', 'page']);
        $jadwal = $this->jadwalSamplingService->getAll($filters);

        return $this->successResponse(
            JadwalSamplingResource::collection($jadwal),
            'Data jadwal sampling berhasil diambil',
            200,
            [
                'current_page' => $jadwal->currentPage(),
                'last_page' => $jadwal->lastPage(),
                'per_page' => $jadwal->perPage(),
                'total' => $jadwal->total(),
            ]
        );
    }

    /**
     * POST /jadwal-sampling
     */
    public function store(StoreJadwalRequest $request): JsonResponse
    {
        $jadwal = $this->jadwalSamplingService->create($request->validated());

        $this->activityLogService->log(
            'create',
            'JadwalSampling',
            $jadwal->id,
            null,
            $jadwal->toArray()
        );

        return $this->createdResponse(
            new JadwalSamplingResource($jadwal),
            'Jadwal sampling berhasil dibuat'
        );
    }
}
