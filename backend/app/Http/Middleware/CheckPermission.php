<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * Usage in routes: ->middleware('permission:permohonan.create')
     */
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 401);
        }

        if (!$user->hasPermission($permission)) {
            return response()->json([
                'success' => false,
                'message' => 'Forbidden: Anda tidak memiliki akses untuk melakukan aksi ini',
            ], 403);
        }

        return $next($request);
    }
}
