<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\ParameterUjiResource;
use App\Services\HasilUjiService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;

class ParameterUjiController extends Controller
{
    use ApiResponseTrait;

    public function __construct(
        protected HasilUjiService $hasilUjiService,
    ) {}

    /**
     * GET /parameter-uji
     */
    public function index(): JsonResponse
    {
        $parameters = $this->hasilUjiService->getAllParameters();

        return $this->successResponse(
            ParameterUjiResource::collection($parameters),
            'Data parameter uji berhasil diambil'
        );
    }
}
