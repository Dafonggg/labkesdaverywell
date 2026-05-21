<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Payment\StorePaymentRequest;
use App\Http\Resources\PaymentResource;
use App\Services\ActivityLogService;
use App\Services\PaymentService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    use ApiResponseTrait;

    public function __construct(
        protected PaymentService $paymentService,
        protected ActivityLogService $activityLogService,
    ) {}

    /**
     * GET /payments
     */
    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(['status', 'per_page', 'page']);
        $payments = $this->paymentService->getAll($filters);

        return $this->successResponse(
            PaymentResource::collection($payments),
            'Data pembayaran berhasil diambil',
            200,
            [
                'current_page' => $payments->currentPage(),
                'last_page' => $payments->lastPage(),
                'per_page' => $payments->perPage(),
                'total' => $payments->total(),
            ]
        );
    }

    /**
     * POST /payments
     */
    public function store(StorePaymentRequest $request): JsonResponse
    {
        $payment = $this->paymentService->create($request->validated());

        $this->activityLogService->log(
            'create',
            'Payment',
            $payment->id,
            null,
            $payment->toArray()
        );

        return $this->createdResponse(
            new PaymentResource($payment),
            'Pembayaran berhasil dicatat'
        );
    }
}
