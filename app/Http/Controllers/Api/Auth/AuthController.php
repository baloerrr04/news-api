<?php

namespace App\Http\Controllers\Api\Auth;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\UserLoginRequest;
use App\Http\Requests\Api\Auth\UserRegisterRequest;
use App\Service\Auth\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{

    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function register(UserRegisterRequest $request): JsonResponse
    {
        try {
            $result = $this->authService->register($request->validated());
            return ApiResponse::success($result, "Register user successfull.", 200);
        } catch (\Exception $e) {
            return ApiResponse::error('Register user failed: ' . $e->getMessage(), 500);
        }
    }

    public function login(UserLoginRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();
            $result = $this->authService->login($data['name'], $data['password']);
            return ApiResponse::success($result, "Login user is successfull.", 200);
        } catch (\Exception $e) {
            return ApiResponse::error('Login user failed: ' . $e->getMessage(), 500);
        }
    }

    public function refreshToken(Request $request)
    {
        $refreshToken = $request->cookie('refresh_token');
        if (!$refreshToken) {
            return ApiResponse::error('Missing refresh token', 401);
        }
        try {
            $newToken = $this->authService->refreshToken($refreshToken);
            return ApiResponse::success($newToken, "Refresh Token is successfull.", 200);
        } catch (\Exception $e) {
            return ApiResponse::error('Refresh token failed: ' . $e->getMessage(), 403);
        }
    }
}
