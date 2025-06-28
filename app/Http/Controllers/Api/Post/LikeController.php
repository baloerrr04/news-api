<?php

namespace App\Http\Controllers\Api\Post;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Services\Post\LikeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    protected $likeService;

    public function __construct(LikeService $likeService)
    {
        $this->likeService = $likeService;
    }

    public function toggle($postId): JsonResponse
    {
        try {
            $userId = Auth::id();
            $result = $this->likeService->toggleLike($userId, $postId);

            return ApiResponse::success($result, $result['liked'] ? 'Post liked.' : 'Post unliked.', 200);
        } catch (\Exception $e) {
            return ApiResponse::error('Failed to like post' . $e->getMessage(), 500);
        }
    }

    public function count($postId): JsonResponse
    {
        try {
            $count = $this->likeService->likeCount($postId);
            return ApiResponse::success($count, 'Count like successfull', 200);
        } catch (\Exception $e) {
            return ApiResponse::error('Failed to count like post:' . $e->getMessage(), 500);
        }
    }
}
