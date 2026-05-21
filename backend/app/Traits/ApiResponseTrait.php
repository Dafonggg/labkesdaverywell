<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponseTrait
{
    /**
     * Return a success JSON response.
     */
    protected function successResponse(mixed $data = null, string $message = 'Success', int $code = 200, array $meta = []): JsonResponse
    {
        $response = [
            'success' => true,
            'message' => $message,
            'data' => $data,
        ];

        if (!empty($meta)) {
            $response['meta'] = $meta;
        }

        return response()->json($response, $code);
    }

    /**
     * Return a created JSON response.
     */
    protected function createdResponse(mixed $data = null, string $message = 'Data berhasil dibuat'): JsonResponse
    {
        return $this->successResponse($data, $message, 201);
    }

    /**
     * Return an error JSON response.
     */
    protected function errorResponse(string $message = 'Error', int $code = 400, array|null $errors = null): JsonResponse
    {
        $response = [
            'success' => false,
            'message' => $message,
        ];

        if ($errors !== null) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $code);
    }

    /**
     * Return a not found JSON response.
     */
    protected function notFoundResponse(string $message = 'Data tidak ditemukan'): JsonResponse
    {
        return $this->errorResponse($message, 404);
    }

    /**
     * Return an unauthorized JSON response.
     */
    protected function unauthorizedResponse(string $message = 'Unauthorized'): JsonResponse
    {
        return $this->errorResponse($message, 401);
    }

    /**
     * Return a forbidden JSON response.
     */
    protected function forbiddenResponse(string $message = 'Forbidden'): JsonResponse
    {
        return $this->errorResponse($message, 403);
    }
}
