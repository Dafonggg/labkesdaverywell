<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Mobile\SyncSamplingRequest;
use App\Http\Requests\Mobile\UploadSampleImageRequest;
use App\Http\Resources\JadwalSamplingResource;
use App\Services\SyncService;
use App\Services\JadwalSamplingService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;

class MobileController extends Controller
{
    use ApiResponseTrait;

    public function __construct(
        protected SyncService $syncService,
        protected JadwalSamplingService $jadwalSamplingService,
    ) {}

    /**
     * GET /mobile/jadwal
     */
    public function getJadwal(): JsonResponse
    {
        $jadwal = $this->jadwalSamplingService->getForMobile(auth()->id());

        return $this->successResponse(
            JadwalSamplingResource::collection($jadwal),
            'Jadwal sampling berhasil diambil'
        );
    }

    /**
     * POST /mobile/sync-sampling
     */
    public function syncSampling(SyncSamplingRequest $request): JsonResponse
    {
        $result = $this->syncService->batchSync($request->validated()['samples']);

        // Auto-update jadwal status to 'berlangsung' for each synced jadwal
        if ($result['success_count'] > 0) {
            $jadwalIds = array_unique(
                array_column($request->validated()['samples'], 'jadwal_sampling_id')
            );
            foreach ($jadwalIds as $jadwalId) {
                try {
                    $jadwal = \App\Models\JadwalSampling::find($jadwalId);
                    if ($jadwal && in_array($jadwal->status, ['scheduled', 'dijadwalkan'])) {
                        $this->jadwalSamplingService->updateStatus($jadwalId, 'berlangsung');
                    }
                } catch (\Exception $e) {
                    // Non-critical — log but don't fail the response
                    \Illuminate\Support\Facades\Log::warning("Failed to update jadwal status: {$e->getMessage()}");
                }
            }
        }

        return $this->successResponse([
            'synced' => $result['success_count'],
            'failed' => $result['failed_count'],
            'errors' => $result['errors'] ?? [],
            'sample_ids' => $result['sample_ids'] ?? [], // sync_id => sample_id
        ], 'Sinkronisasi berhasil');
    }

    /**
     * POST /mobile/upload-sample-image
     */
    public function uploadSampleImage(UploadSampleImageRequest $request): JsonResponse
    {
        $file = $this->syncService->uploadImage(
            $request->validated()['sample_id'],
            $request->file('image')
        );

        return $this->createdResponse([
            'file_path' => $file->file_path,
            'file_name' => $file->file_name,
        ], 'Foto berhasil diupload');
    }
}
