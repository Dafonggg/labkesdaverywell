<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\ParameterUjiResource;
use App\Services\HasilUjiService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ParameterUjiController extends Controller
{
    use ApiResponseTrait;

    public function __construct(
        protected HasilUjiService $hasilUjiService,
    ) {}

    /**
     * GET /parameter-uji
     */
    public function index(Request $request): JsonResponse
    {
        $kategori = $request->query('kategori');
        $tipePengujian = $request->query('tipe_pengujian');

        $parameters = $this->hasilUjiService->getAllParameters($kategori, $tipePengujian);

        return $this->successResponse(
            ParameterUjiResource::collection($parameters),
            'Data parameter uji berhasil diambil'
        );
    }
}
