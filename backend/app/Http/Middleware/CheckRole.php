<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * Usage in routes: ->middleware('role:admin,petugas_lab')
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 401);
        }

        $userRoleCode = $user->role?->code;

        if (!in_array($userRoleCode, $roles)) {
            return response()->json([
                'success' => false,
                'message' => 'Forbidden: Role Anda tidak memiliki akses',
            ], 403);
        }

        return $next($request);
    }
}
