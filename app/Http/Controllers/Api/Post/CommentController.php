<?php

namespace App\Http\Controllers\Api\Post;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Post\CommentStoreRequest;
use App\Http\Requests\Api\Post\CommentUpdateRequest;
use App\Services\Post\CommentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    protected $commentService;

    public function __construct(CommentService $commentService)
    {
        $this->commentService = $commentService;
    }

    public function index($postId): JsonResponse
    {
        try {
            $comments = $this->commentService->getAll($postId);
            return ApiResponse::success($comments);
        } catch (\Exception $e) {
            return ApiResponse::error('Failed to retrieve posts.', 500);
        }
    }

    public function show($id): JsonResponse
    {
        try {
            $comment = $this->commentService->getById($id);
            if (!$comment) {
                return ApiResponse::error('Comment not found.', 404);
            }
            return ApiResponse::success($comment);
        } catch (\Exception $e) {
            return ApiResponse::error('Failed to retrieve comment.', 500);
        }
    }

    public function store(CommentStoreRequest $request, $postId): JsonResponse
    {
        try {
            $comment = $this->commentService->store($postId, $request->validated());
            return ApiResponse::success($comment, 'Post created successfully.', 201);
        } catch (\Exception $e) {
            return ApiResponse::error('Failed to create comment.' . $e->getMessage(), 500);
        }
    }

    public function update(CommentUpdateRequest $request, $postId, $commentId): JsonResponse
    {
        try {
            $data = $request->validated();
            $userId = Auth::id();
            $comment = $this->commentService->update($commentId, $postId, $userId, $data);
            return ApiResponse::success($comment, 'Comment updated successfully.');
        } catch (\Exception $e) {
            return ApiResponse::error('Comment to update post.' . $e->getMessage(), 500);
        }
    }

    public function destroy($id): JsonResponse
    {
        try {
            $this->commentService->delete($id);
            return ApiResponse::success(null, 'Post deleted successfully.');
        } catch (\Exception $e) {
            return ApiResponse::error('Failed to delete post.' . $e->getMessage(), 500);
        }
    }
}
