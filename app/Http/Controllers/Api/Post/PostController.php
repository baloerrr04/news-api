<?php

namespace App\Http\Controllers\Api\Post;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Post\PostStoreRequest;
use App\Http\Requests\Api\Post\PostUpdateRequest;
use App\Services\Post\PostService;
use Illuminate\Http\JsonResponse;


class PostController extends Controller
{
    protected $postService;

    public function __construct(PostService $postService)
    {
        $this->postService = $postService;
    }

    public function index(): JsonResponse
    {
        try {
            $posts = $this->postService->getAll();
            return ApiResponse::success($posts);
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
            return ApiResponse::success($post);
        } catch (\Exception $e) {
            return ApiResponse::error('Failed to retrieve post.', 500);
        }
    }

    public function store(PostStoreRequest $request)
    {
        try {

            $post = $this->postService->store($request->validated());
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
            return ApiResponse::success($user, 'Post updated successfully.');
        } catch (\Exception $e) {
            return ApiResponse::error('Failed to update post.' . $e->getMessage(), 500);
        }
    }

    public function destroy($id): JsonResponse
    {
        try {
            $this->postService->delete($id);
            return ApiResponse::success(null, 'Post deleted successfully.');
        } catch (\Exception $e) {
            return ApiResponse::error('Failed to delete post.' . $e->getMessage(), 500);
        }
    }
}
