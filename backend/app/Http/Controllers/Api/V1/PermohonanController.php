<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Permohonan\StorePermohonanRequest;
use App\Http\Requests\Permohonan\UpdatePermohonanRequest;
use App\Http\Resources\PermohonanResource;
use App\Services\ActivityLogService;
use App\Services\PermohonanService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PermohonanController extends Controller
{
    use ApiResponseTrait;

    public function __construct(
        protected PermohonanService $permohonanService,
        protected ActivityLogService $activityLogService,
    ) {}

    /**
     * GET /permohonan
     */
    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(['search', 'status', 'per_page', 'page']);
        $permohonan = $this->permohonanService->getAll($filters);

        return $this->successResponse(
            PermohonanResource::collection($permohonan),
            'Data permohonan berhasil diambil',
            200,
            [
                'current_page' => $permohonan->currentPage(),
                'last_page' => $permohonan->lastPage(),
                'per_page' => $permohonan->perPage(),
                'total' => $permohonan->total(),
            ]
        );
    }

    /**
     * POST /permohonan
     */
    public function store(StorePermohonanRequest $request): JsonResponse
    {
        $permohonan = $this->permohonanService->create($request->validated());

        $this->activityLogService->log(
            'create',
            'PermohonanPengujian',
            $permohonan->id,
            null,
            $permohonan->toArray()
        );

        return $this->createdResponse(
            new PermohonanResource($permohonan),
            'Permohonan berhasil dibuat'
        );
    }

    /**
     * GET /permohonan/{id}
     */
    public function show(string $id): JsonResponse
    {
        $permohonan = $this->permohonanService->getById($id);

        return $this->successResponse(
            new PermohonanResource($permohonan),
            'Detail permohonan berhasil diambil'
        );
    }

    /**
     * PUT /permohonan/{id}
     */
    public function update(UpdatePermohonanRequest $request, string $id): JsonResponse
    {
        $oldPermohonan = $this->permohonanService->getById($id);
        $oldValues = $oldPermohonan->toArray();

        $permohonan = $this->permohonanService->update($id, $request->validated());

        $this->activityLogService->log(
            'update',
            'PermohonanPengujian',
            $permohonan->id,
            $oldValues,
            $permohonan->toArray()
        );

        return $this->successResponse(
            new PermohonanResource($permohonan),
            'Permohonan berhasil diperbarui'
        );
    }

    /**
     * DELETE /permohonan/{id}
     */
    public function destroy(string $id): JsonResponse
    {
        $this->activityLogService->log('delete', 'PermohonanPengujian', $id);

        $this->permohonanService->delete($id);

        return $this->successResponse(null, 'Permohonan berhasil dihapus');
    }

    /**
     * POST /permohonan/{id}/verify
     * Admin verifies permohonan and transitions PENDING → WAITING_PAYMENT.
     */
    public function verify(string $id): JsonResponse
    {
        $permohonan = $this->permohonanService->transitionToWaitingPayment($id);

        $this->activityLogService->log(
            'update',
            'PermohonanPengujian',
            $permohonan->id,
            null,
            $permohonan->toArray()
        );

        return $this->successResponse(
            new PermohonanResource($permohonan),
            'Permohonan berhasil diverifikasi. Status diubah ke Menunggu Pembayaran.'
        );
    }
}
