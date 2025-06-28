<?php

namespace App\Http\Controllers\Api\Category;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Category\CategoryStoreRequest;
use App\Http\Requests\Api\Category\CategoryUpdateRequest;
use App\Services\Category\CategoryService;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    protected $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    public function index(): JsonResponse
    {
        try {
            $categories = $this->categoryService->getAll();
            return ApiResponse::success($categories);
        } catch (\Exception $e) {
            return ApiResponse::error('Failed to retrieve categories.', 500);
        }
    }

    public function show($id): JsonResponse
    {
        try {
            $category = $this->categoryService->getById($id);
            if (!$category) {
                return ApiResponse::error('Category not found.', 404);
            }
            return ApiResponse::success($category);
        } catch (\Exception $e) {
            return ApiResponse::error('Failed to retrieve categories.', 500);
        }
    }

    public function store(CategoryStoreRequest $request): JsonResponse
    {
       try {
            $category = $this->categoryService->store($request->validated());
            return ApiResponse::success($category, 'Category created successfully.', 201);
        } catch (\Exception $e) {
            return ApiResponse::error('Failed to create category.' . $e->getMessage(), 500);
        }
    }

    public function update(CategoryUpdateRequest $request, $id): JsonResponse
    {
       try {
            $data = $request->validated();
            $category = $this->categoryService->update($id, $data);
            return ApiResponse::success($category, 'Category updated successfully.');
        } catch (\Exception $e) {
            return ApiResponse::error('Category to update post.' . $e->getMessage(), 500);
        }
    }

    public function destroy($id): JsonResponse
    {
        try {
            $this->categoryService->delete($id);
            return ApiResponse::success(null, 'Category deleted successfully.');
        } catch (\Exception $e) {
            return ApiResponse::error('Failed to delete category.' . $e->getMessage(), 500);
        }
    }
}
