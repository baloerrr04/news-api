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

    /**
     * @OA\Get(
     *     path="/api/posts",
     *     tags={"Posts"},
     *     summary="Get all posts with optional filters and pagination",
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="Search post by title",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="category_id",
     *         in="query",
     *         description="Filter by category ID",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Pagination page number",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of posts"
     *     )
     * )
     */
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


    /**
     * @OA\Get(
     *     path="/api/posts/{id}",
     *     tags={"Posts"},
     *     summary="Get post by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Post ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Post detail"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Post not found"
     *     )
     * )
     */
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

    /**
     * @OA\Post(
     *     path="/api/posts",
     *     tags={"Posts"},
     *     summary="Create a new post",
     *     description="Create a post with title, content, category, and thumbnail.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"title", "content", "category_id"},
     *                 @OA\Property(property="title", type="string", example="AI is amazing"),
     *                 @OA\Property(property="content", type="string", example="Exploring GPT-4's power"),
     *                 @OA\Property(property="category_id", type="integer", example=1),
     *                 @OA\Property(property="thumbnail", type="string", format="binary")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Post created successfully"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation failed"
     *     )
     * )
     */
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

    /**
     * @OA\Put(
     *     path="/api/posts/{id}",
     *     tags={"Posts"},
     *     summary="Update a post",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Post ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(property="title", type="string", example="Updated title"),
     *                 @OA\Property(property="content", type="string", example="Updated content"),
     *                 @OA\Property(property="category_id", type="integer", example=1),
     *                 @OA\Property(property="thumbnail", type="string", format="binary")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Post updated successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Post not found"
     *     )
     * )
     */
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

    /**
     * @OA\Delete(
     *     path="/api/posts/{id}",
     *     tags={"Posts"},
     *     summary="Delete a post",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Post ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Post deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Post not found"
     *     )
     * )
     */
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
