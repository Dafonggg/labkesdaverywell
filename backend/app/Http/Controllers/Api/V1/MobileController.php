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

        return $this->successResponse([
            'success_count' => $result['success_count'],
            'failed_count' => $result['failed_count'],
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
