<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\NotificationResource;
use App\Services\NotificationService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    use ApiResponseTrait;

    public function __construct(
        protected NotificationService $notificationService,
    ) {}

    /**
     * GET /notifications
     */
    public function index(): JsonResponse
    {
        $notifications = $this->notificationService->getForUser(auth()->id());

        return $this->successResponse(
            NotificationResource::collection($notifications),
            'Notifikasi berhasil diambil',
            200,
            [
                'current_page' => $notifications->currentPage(),
                'last_page' => $notifications->lastPage(),
                'per_page' => $notifications->perPage(),
                'total' => $notifications->total(),
            ]
        );
    }

    /**
     * POST /notifications/read
     */
    public function markAsRead(Request $request): JsonResponse
    {
        $ids = $request->input('ids');
        $count = $this->notificationService->markAsRead(auth()->id(), $ids);

        return $this->successResponse([
            'updated_count' => $count,
        ], 'Notifikasi berhasil ditandai sudah dibaca');
    }
}
