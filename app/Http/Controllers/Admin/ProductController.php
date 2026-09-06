<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Product\StoreProductRequest;
use App\Http\Requests\Admin\Product\UpdateProductRequest;
use App\Models\Category;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\ProductDetail;
use Illuminate\Support\Str;
use App\Services\ImageUploadService;
use App\Models\Image;


class ProductController extends Controller
{
    public function __construct(
        private readonly ProductService $productService,
        private readonly ImageUploadService $imageService
    ) {}

    public function index(): View
    {
        $products = $this->productService->allForAdmin();
        return view('backend.products.index', compact('products'));
    }

    public function create(): View
    {
        $categories = Category::all();
        return view('backend.products.create', compact('categories'));
    }

    public function store(StoreProductRequest $request)
    {
        //dd($request->all());
        $data = $request->validated();
        $data['slug'] = Str::slug($data['name']);
        $data['v_image'] = $request->file('v_image', []);

        // Handle main image
        if ($request->hasFile('image')) {
            $mainImage = $this->imageService->upload(
                $request->file('image'),
                ImageUploadService::TYPE_PRODUCTS,
                $data['slug']
            );
            $data['image_id'] = $mainImage->id;
        }

        $product = $this->productService->create($data);

        return redirect()->route('admin.products.index')
            ->with('success', 'Product created successfully.');
    }

    public function edit(Product $product): View
    {
        $categories = Category::all();
        return view('backend.products.edit', compact('product', 'categories'));
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        //dd($request->all());
        $data = $request->validated();
       // dd($data);
        $data['slug'] = Str::slug($data['name']);

        // Handle main image
        if ($request->hasFile('image')) {
            if ($product->image_id && $product->image && is_object($product->image)) {
                $this->imageService->delete($product->image->path . '/' . $product->image->name);
            }
            $mainImage = $this->imageService->upload(
                $request->file('image'),
                ImageUploadService::TYPE_PRODUCTS,
                $data['slug']
            );
            $data['image_id'] = $mainImage->id;
        }

        $this->productService->update($product->id, $data);

        return redirect()->route('admin.products.index')
            ->with('success', 'Product updated successfully.');
    }

    public function copy(Product $product)
    {
        $newProduct = $this->productService->copyProduct($product);

        if ($newProduct) {
            session()->flash('success', 'Product is copied!');
        } else {
            session()->flash('error', 'Product copy failed!');
        }

        return redirect()->route('admin.products.index');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $this->productService->delete($product->id);

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Product deleted successfully.');
    }

    public function deleteGalleryImage(Image $image)
    {
        try {
            // Get the product slug from the image path
            $parts = explode('/', $image->path);
            $productSlug = $parts[2] ?? '';

            // Delete the files
            $galleryPath = public_path('images/products/' . $productSlug . '/gallery/' . $image->name);
            $thumbPath = public_path('images/products/' . $productSlug . '/thumbs/' . $image->name);

            if (file_exists($galleryPath)) {
                unlink($galleryPath);
            }
            if (file_exists($thumbPath)) {
                unlink($thumbPath);
            }

            // Delete from database
            $image->delete();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function deleteMainImage(Product $product)
    {
        if ($product->image) {
            // Remove image file and DB record as needed
            $this->imageService->delete($product->image->path . '/' . $product->image->name);
            $product->image->delete();
            $product->image_id = null;
            $product->save();
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false, 'message' => 'No image found']);
    }
}
