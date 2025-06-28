<?php

namespace App\Http\Controllers\Api\User;

use App\DTOs\User\UserDto;
use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\User\UserStoreRequest;
use App\Http\Requests\Api\User\UserUpdateRequest;
use App\Services\User\UserService;
use Illuminate\Http\JsonResponse;

use Throwable;

class UserController extends Controller
{

    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index(): JsonResponse {
        try {
            $users = $this->userService->getAll();
            return ApiResponse::success($users);
        } catch (\Throwable $e) {
            return ApiResponse::error('Failed to retrieve users.', 500);
        }
    }

    public function show($id): JsonResponse
    {
        try {
            $user = $this->userService->getById($id);
            if (!$user) {
                return ApiResponse::error('User not found.', 404);
            }
            return ApiResponse::success($user);
        } catch (Throwable $e) {
            return ApiResponse::error('Failed to retrieve user.', 500);
        }
    }

    public function store(UserStoreRequest $request): JsonResponse
    {
        try {
            $user = $this->userService->store($request->validated());
            return ApiResponse::success($user, 'User created successfully.', 201);
        } catch (\Exception $e) {
            return ApiResponse::error('Failed to create user.' . $e->getMessage(), 500);
        }
    }

     public function update(UserUpdateRequest $request, $id): JsonResponse
    {
        try {
            $data = $request->validated();
            if (isset($data['password'])) {
                $data['password'] = bcrypt($data['password']);
            }
            $user = $this->userService->update($id, $data);
            return ApiResponse::success($user, 'User updated successfully.');
        } catch (\Exception $e) {
            return ApiResponse::error('Failed to update user.' . $e->getMessage(), 500);
        }
    }

    public function destroy($id): JsonResponse
    {
        try {
            $this->userService->delete($id);
            return ApiResponse::success(null, 'User deleted successfully.');
        } catch (\Exception $e) {
            return ApiResponse::error('Failed to delete user.' . $e->getMessage(), 500);
        }
    }


}
