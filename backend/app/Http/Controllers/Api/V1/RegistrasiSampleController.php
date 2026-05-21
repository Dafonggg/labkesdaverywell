<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegistrasiSample\StoreRegistrasiRequest;
use App\Http\Resources\RegistrasiSampleResource;
use App\Services\ActivityLogService;
use App\Services\RegistrasiSampleService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RegistrasiSampleController extends Controller
{
    use ApiResponseTrait;

    public function __construct(
        protected RegistrasiSampleService $registrasiSampleService,
        protected ActivityLogService $activityLogService,
    ) {}

    /**
     * GET /registrasi-sample
     */
    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(['per_page', 'page']);
        $registrasi = $this->registrasiSampleService->getAll($filters);

        return $this->successResponse(
            RegistrasiSampleResource::collection($registrasi),
            'Data registrasi sampel berhasil diambil',
            200,
            [
                'current_page' => $registrasi->currentPage(),
                'last_page' => $registrasi->lastPage(),
                'per_page' => $registrasi->perPage(),
                'total' => $registrasi->total(),
            ]
        );
    }

    /**
     * POST /registrasi-sample
     */
    public function store(StoreRegistrasiRequest $request): JsonResponse
    {
        $registrasi = $this->registrasiSampleService->register($request->validated());

        $this->activityLogService->log(
            'create',
            'RegistrasiSample',
            $registrasi->id,
            null,
            $registrasi->toArray()
        );

        return $this->createdResponse(
            new RegistrasiSampleResource($registrasi),
            'Registrasi berhasil'
        );
    }
}
