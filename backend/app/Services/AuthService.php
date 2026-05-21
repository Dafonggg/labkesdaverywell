<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthService
{
    /**
     * Authenticate user and return tokens.
     *
     * @return array{user: User, token: string, refresh_token: string}
     * @throws \Exception
     */
    public function login(array $credentials): array
    {
        $user = User::where('email', $credentials['email'])->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            throw new \Exception('Email atau password salah', 401);
        }

        if (!$user->is_active) {
            throw new \Exception('Akun Anda tidak aktif, silakan hubungi administrator', 403);
        }

        $token = JWTAuth::fromUser($user);

        // Use a separate token as refresh token with custom claim
        $refreshToken = JWTAuth::claims(['refresh' => true])->fromUser($user);

        // Update last login
        $user->update(['last_login_at' => now()]);

        $user->load('role');

        return [
            'user' => $user,
            'token' => $token,
            'refresh_token' => $refreshToken,
        ];
    }

    /**
     * Logout user (invalidate token).
     */
    public function logout(): void
    {
        JWTAuth::invalidate(JWTAuth::getToken());
    }

    /**
     * Refresh JWT token.
     *
     * @return array{token: string, refresh_token: string}
     */
    public function refreshToken(): array
    {
        $token = JWTAuth::refresh(JWTAuth::getToken());

        return [
            'token' => $token,
        ];
    }

    /**
     * Get authenticated user with role.
     */
    public function me(): User
    {
        /** @var User $user */
        $user = Auth::user();
        $user->load('role');

        return $user;
    }
}
