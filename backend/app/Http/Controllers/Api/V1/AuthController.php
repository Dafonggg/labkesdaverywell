<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\UserResource;
use App\Services\AuthService;
use App\Traits\ActivityLoggable;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    use ApiResponseTrait, ActivityLoggable;

    public function __construct(
        protected AuthService $authService,
    ) {}

    /**
     * POST /auth/login
     */
    public function login(LoginRequest $request): JsonResponse
    {
        try {
            $result = $this->authService->login($request->validated());

            $this->logActivity('login', 'User', $result['user']->id);

            return $this->successResponse([
                'user' => new UserResource($result['user']),
                'token' => $result['token'],
                'refresh_token' => $result['refresh_token'],
            ], 'Login berhasil');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), (int) $e->getCode() ?: 401);
        }
    }

    /**
     * POST /auth/logout
     */
    public function logout(): JsonResponse
    {
        try {
            $this->logActivity('logout', 'User', auth()->id());
            $this->authService->logout();

            return $this->successResponse(null, 'Logout berhasil');
        } catch (\Exception $e) {
            return $this->errorResponse('Logout gagal', 500);
        }
    }

    /**
     * POST /auth/refresh
     */
    public function refresh(): JsonResponse
    {
        try {
            $result = $this->authService->refreshToken();

            return $this->successResponse($result, 'Token berhasil diperbarui');
        } catch (\Exception $e) {
            return $this->unauthorizedResponse('Token tidak valid atau telah kedaluwarsa');
        }
    }

    /**
     * GET /auth/me
     */
    public function me(): JsonResponse
    {
        $user = $this->authService->me();

        return $this->successResponse(
            new UserResource($user),
            'Data user berhasil diambil'
        );
    }
}
