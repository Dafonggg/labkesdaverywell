<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\Role;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    use ApiResponseTrait;

    /**
     * GET /users/petugas-lapangan
     * Return all active users with role code = petugas_lapangan.
     */
    public function petugasLapangan(): JsonResponse
    {
        $role = Role::where('code', 'petugas_lapangan')->first();

        if (! $role) {
            return $this->successResponse([], 'Role petugas lapangan tidak ditemukan');
        }

        // Return all fields needed by UserResource
        $users = $role->users()
            ->where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name', 'email', 'phone', 'is_active', 'last_login_at', 'created_at']);

        return $this->successResponse(
            UserResource::collection($users),
            'Data petugas lapangan berhasil diambil'
        );
    }
}
