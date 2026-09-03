<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Product\StoreProductRequest;
use App\Http\Requests\Api\Product\UpdateProductRequest;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct(
        private readonly ProductService $productService
    ) {
    }

    public function index(Request $request): JsonResponse
    {
        $products = $this->productService->paginate(
            $request->input('per_page', 15)
        );

        return response()->json([
            'data' => $products,
        ]);
    }

    public function store(StoreProductRequest $request): JsonResponse
    {
        $product = $this->productService->create(
            $request->validated(),
            $request->file('image')
        );

        return response()->json([
            'message' => 'Product created successfully',
            'data' => $product,
        ], 201);
    }

    public function show(Product $product): JsonResponse
    {
        $product->load(['category', 'image']);

        return response()->json([
            'data' => $product,
        ]);
    }

    public function update(UpdateProductRequest $request, Product $product): JsonResponse
    {
        $product = $this->productService->update(
            $product->id,
            $request->validated(),
            $request->file('image')
        );

        return response()->json([
            'message' => 'Product updated successfully',
            'data' => $product,
        ]);
    }

    public function destroy(Product $product): JsonResponse
    {
        $this->productService->delete($product->id);

        return response()->json([
            'message' => 'Product deleted successfully',
        ]);
    }

    public function featured(): JsonResponse
    {
        $products = $this->productService->getFeatured();

        return response()->json([
            'data' => $products,
        ]);
    }

    public function byCategory(Request $request, int $categoryId): JsonResponse
    {
        $products = $this->productService->getByCategory(
            $categoryId,
            $request->input('per_page', 15)
        );

        return response()->json([
            'data' => $products,
        ]);
    }

    public function search(Request $request): JsonResponse
    {
        $products = $this->productService->search(
            $request->input('query'),
            $request->input('per_page', 15)
        );

        return response()->json([
            'data' => $products,
        ]);
    }

    public function related(Product $product): JsonResponse
    {
        $products = $this->productService->getRelated($product);

        return response()->json([
            'data' => $products,
        ]);
    }
}
