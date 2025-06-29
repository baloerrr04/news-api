<?php

namespace App\Http\Controllers\Api\Post;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Post\PostStoreRequest;
use App\Http\Requests\Api\Post\PostUpdateRequest;
use App\Services\ActivityLogger\ActivityLoggerService;
use App\Services\Post\PostService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PostController extends Controller
{
    protected $postService;
    protected $activityLoggerService;

    public function __construct(PostService $postService, ActivityLoggerService $activityLoggerService)
    {
        $this->postService = $postService;
        $this->activityLoggerService = $activityLoggerService;
    }

    public function index(Request $request): JsonResponse
    {
        try {
            $posts = $this->postService->filter($request->query());
            $this->activityLoggerService->logAction('get_all_post', null, 'User retrieved all posts');
            return ApiResponse::success($posts, 'Posts retrieved successfully.');
        } catch (\Exception $e) {
            return ApiResponse::error('Failed to retrieve posts.', 500);
        }
    }

    public function show($id)
    {
        try {
            $post = $this->postService->getById($id);
            if (!$post) {
                return ApiResponse::error('Post not found.', 404);
            }
            $this->activityLoggerService->logAction('get_post_by_id', null, 'User retrieved all post by id');
            return ApiResponse::success($post);
        } catch (\Exception $e) {
            return ApiResponse::error('Failed to retrieve post.', 500);
        }
    }

    public function store(PostStoreRequest $request)
    {
        try {

            $post = $this->postService->store($request->validated());
            $this->activityLoggerService->logAction('create_post', null, 'Post created by user');
            return ApiResponse::success($post, 'Post created successfully.', 201);
        } catch (\Exception $e) {
            return ApiResponse::error('Failed to create post.' . $e->getMessage(), 500);
        }
    }

    public function update(PostUpdateRequest $request, $id): JsonResponse
    {
        try {

            $data = $request->validated();
            $user = $this->postService->update($id, $data);
            $this->activityLoggerService->logAction('update_post', null, 'Post updated by user');
            return ApiResponse::success($user, 'Post updated successfully.');
        } catch (\Exception $e) {
            return ApiResponse::error('Failed to update post.' . $e->getMessage(), 500);
        }
    }

    public function destroy($id): JsonResponse
    {
        try {
            $this->postService->delete($id);
            $this->activityLoggerService->logAction('delete_post', null, 'Post deleted by user');
            return ApiResponse::success(null, 'Post deleted successfully.');
        } catch (\Exception $e) {
            return ApiResponse::error('Failed to delete post.' . $e->getMessage(), 500);
        }
    }
}
