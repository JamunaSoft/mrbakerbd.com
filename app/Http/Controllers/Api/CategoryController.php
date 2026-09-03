<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Category\StoreCategoryRequest;
use App\Http\Requests\Api\Category\UpdateCategoryRequest;
use App\Models\Category;
use App\Services\CategoryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function __construct(
        private readonly CategoryService $categoryService
    ) {
    }

    public function index(Request $request): JsonResponse
    {
        $categories = $this->categoryService->paginate(
            $request->input('per_page', 15)
        );

        return response()->json([
            'data' => $categories,
        ]);
    }

    public function store(StoreCategoryRequest $request): JsonResponse
    {
        $category = $this->categoryService->create($request->validated());

        return response()->json([
            'message' => 'Category created successfully',
            'data' => $category,
        ], 201);
    }

    public function show(Category $category): JsonResponse
    {
        return response()->json([
            'data' => $category,
        ]);
    }

    public function update(UpdateCategoryRequest $request, Category $category): JsonResponse
    {
        $category = $this->categoryService->update($category->id, $request->validated());

        return response()->json([
            'message' => 'Category updated successfully',
            'data' => $category,
        ]);
    }

    public function destroy(Category $category): JsonResponse
    {
        $this->categoryService->delete($category->id);

        return response()->json([
            'message' => 'Category deleted successfully',
        ]);
    }
}
