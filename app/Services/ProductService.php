<?php

namespace App\Services;

use App\Models\Product;
use App\Repositories\Interfaces\ProductRepositoryInterface;
use App\Services\Interfaces\ImageUploadServiceInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class ProductService
{
    public function __construct(
        private readonly ProductRepositoryInterface $productRepository,
        private readonly ImageUploadServiceInterface $imageService
    ) {
    }

    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return $this->productRepository->paginate($perPage);
    }

    public function create(array $data, ?UploadedFile $image = null): Product
    {
        // Convert empty string prices to null
        if (isset($data['regular_price']) && $data['regular_price'] === '') $data['regular_price'] = null;
        if (isset($data['special_price']) && $data['special_price'] === '') $data['special_price'] = null;

        return DB::transaction(function () use ($data, $image) {
            if ($image) {
                $imageModel = $this->imageService->upload($image, ImageUploadService::TYPE_PRODUCTS, $data['slug']);
                $data['image_id'] = $imageModel->id;
            }

            $data['slug'] = Str::slug($data['name']);
            $data['is_featured'] = $data['featured'] ?? false;
            $data['is_active'] = $data['status'] ?? true;
            $data['type'] = $data['type'] ?? 1;

            $product = $this->productRepository->create($data);

            // Handle variable product details
            if ($data['type'] == 2 && isset($data['size']) && is_array($data['size'])) {
                foreach ($data['size'] as $index => $size) {
                    $regular = $data['v_regular_price'][$index];
                    $special = $data['v_special_price'][$index] ?? null;
                    if ($regular === '') $regular = null;
                    if ($special === '') $special = null;
                    $product->details()->create([
                        'size' => $size,
                        'regular_price' => $regular,
                        'special_price' => $special,
                    ]);
                }
            }

            // Handle gallery images
            if (isset($data['v_image']) && is_array($data['v_image'])) {
                foreach ($data['v_image'] as $galleryImage) {
                    if ($galleryImage instanceof UploadedFile) {
                        $imageModel = $this->imageService->upload($galleryImage, ImageUploadService::TYPE_PRODUCTS, $data['slug'], true);
                        $product->images()->attach($imageModel->id);
                    }
                }
            }

            return $product;
        });
    }

    public function update(int $id, array $data, ?UploadedFile $image = null): Product
    {
        // Convert empty string prices to null
        if (isset($data['regular_price']) && $data['regular_price'] === '') $data['regular_price'] = null;
        if (isset($data['special_price']) && $data['special_price'] === '') $data['special_price'] = null;

        return DB::transaction(function () use ($id, $data, $image) {
            $product = $this->productRepository->find($id);
            if (!$product) {
                throw new \RuntimeException('Product not found');
            }

            $oldSlug = $product->slug;
            $newSlug = isset($data['name']) ? Str::slug($data['name']) : $oldSlug;

            if ($image) {
                $imageModel = $this->imageService->upload($image, ImageUploadService::TYPE_PRODUCTS, $newSlug);
                $data['image_id'] = $imageModel->id;
                if ($product->image) {
                    $this->imageService->delete($product->image->path . '/' . $product->image->name);
                }
            } else if ($oldSlug !== $newSlug && $product->image) {
                // Move existing product image to new slug directory if slug changed but image didn't
                $oldImagePath = public_path($product->image->path . '/' . $product->image->name);
                $newImagePath = public_path('images/products/' . $newSlug . '/' . $product->image->name);
                $oldThumbPath = public_path('images/products/' . $oldSlug . '/thumbs/' . $product->image->name);
                $newThumbPath = public_path('images/products/' . $newSlug . '/thumbs/' . $product->image->name);

                // Create new directories if they don't exist
                if (!file_exists(dirname($newImagePath))) {
                    mkdir(dirname($newImagePath), 0755, true);
                }
                if (!file_exists(dirname($newThumbPath))) {
                    mkdir(dirname($newThumbPath), 0755, true);
                }

                // Move files if they exist
                if (file_exists($oldImagePath)) {
                    rename($oldImagePath, $newImagePath);
                }
                if (file_exists($oldThumbPath)) {
                    rename($oldThumbPath, $newThumbPath);
                }

                // Update image path in database
                $product->image->update([
                    'path' => 'images/products/' . $newSlug
                ]);
            }

            if (isset($data['name'])) {
                $data['slug'] = $newSlug;
            }

            $product = $this->productRepository->update($id, $data);

            // Move existing gallery images to new slug directory if slug changed
            if ($oldSlug !== $newSlug) {
                $galleryImages = $product->images;
                foreach ($galleryImages as $galleryImage) {
                    $oldPath = public_path('images/products/' . $oldSlug . '/gallery/' . $galleryImage->name);
                    $newPath = public_path('images/products/' . $newSlug . '/gallery/' . $galleryImage->name);
                    $oldThumbPath = public_path('images/products/' . $oldSlug . '/thumbs/' . $galleryImage->name);
                    $newThumbPath = public_path('images/products/' . $newSlug . '/thumbs/' . $galleryImage->name);

                    // Create new directories if they don't exist
                    if (!file_exists(dirname($newPath))) {
                        mkdir(dirname($newPath), 0755, true);
                    }
                    if (!file_exists(dirname($newThumbPath))) {
                        mkdir(dirname($newThumbPath), 0755, true);
                    }

                    // Move files if they exist
                    if (file_exists($oldPath)) {
                        rename($oldPath, $newPath);
                    }
                    if (file_exists($oldThumbPath)) {
                        rename($oldThumbPath, $newThumbPath);
                    }

                    // Update image path in database
                    $galleryImage->update([
                        'path' => 'images/products/' . $newSlug . '/gallery'
                    ]);
                }

                // Delete old slug directories after moving all files
                $oldGalleryDir = public_path('images/products/' . $oldSlug . '/gallery');
                $oldThumbsDir = public_path('images/products/' . $oldSlug . '/thumbs');
                $oldMainDir = public_path('images/products/' . $oldSlug);

                // Remove directories if they exist and are empty
                if (is_dir($oldGalleryDir) && count(glob("$oldGalleryDir/*")) === 0) {
                    rmdir($oldGalleryDir);
                }
                if (is_dir($oldThumbsDir) && count(glob("$oldThumbsDir/*")) === 0) {
                    rmdir($oldThumbsDir);
                }
                if (is_dir($oldMainDir) && count(glob("$oldMainDir/*")) === 0) {
                    rmdir($oldMainDir);
                }
            }

            // Delete all existing details first
            $product->details()->delete();

            // Add new details
            foreach ($data['size'] as $index => $size) {
                if (!empty($size)) {
                    $regular = $data['v_regular_price'][$index];
                    $special = $data['v_special_price'][$index] ?? null;
                    if ($regular === '') $regular = null;
                    if ($special === '') $special = null;
                    $product->details()->create([
                        'size' => $size,
                        'regular_price' => $regular,
                        'special_price' => $special,
                    ]);
                }
            }

            // Handle gallery images
            if (isset($data['v_image']) && is_array($data['v_image'])) {
                foreach ($data['v_image'] as $galleryImage) {
                    if ($galleryImage instanceof UploadedFile) {
                        $imageModel = $this->imageService->upload($galleryImage, ImageUploadService::TYPE_PRODUCTS, $data['slug'], true);
                        $product->images()->attach($imageModel->id);
                    }
                }
            }

            // Handle removed gallery images
            if (isset($data['removed_images'])) {
                $removedImages = json_decode($data['removed_images'], true);
                if (is_array($removedImages)) {
                    $product->images()->detach($removedImages);
                }
            }

            return $product;
        });
    }

    public function delete(int $id): bool
    {
        $product = $this->productRepository->find($id);
        if (!$product) {
            return false;
        }

        // Delete product image and its thumbnail
        if ($product->image) {
            $this->imageService->delete($product->image->path . '/' . $product->image->name);
        }

        // Delete all gallery images and their thumbnails
        foreach ($product->images as $galleryImage) {
            $this->imageService->delete($galleryImage->path);
        }

        // Delete the entire product directory
        $productDir = public_path('images/products/' . $product->slug);
        if (is_dir($productDir)) {
            $this->deleteDirectory($productDir);
        }

        return $this->productRepository->delete($id);
    }

    /**
     * Recursively delete a directory and its contents
     */
    private function deleteDirectory(string $dir): bool
    {
        if (!is_dir($dir)) {
            return false;
        }

        $files = array_diff(scandir($dir), ['.', '..']);
        foreach ($files as $file) {
            $path = $dir . '/' . $file;
            is_dir($path) ? $this->deleteDirectory($path) : unlink($path);
        }

        return rmdir($dir);
    }

    public function getFeatured(int $limit = 8): Collection
    {
        return $this->productRepository->getFeatured($limit);
    }

    public function getByCategory(int $categoryId, int $perPage = 15): LengthAwarePaginator
    {
        return $this->productRepository->getByCategory($categoryId, $perPage);
    }

    public function getBySlug(string $slug): ?Product
    {
        return $this->productRepository->getBySlug($slug);
    }

    public function search(string $query, int $perPage = 15): LengthAwarePaginator
    {
        return $this->productRepository->search($query, $perPage);
    }

    public function getRelated(Product $product, int $limit = 4): Collection
    {
        return $this->productRepository->getRelated($product, $limit);
    }

    public function updateStock(int $id, int $quantity): bool
    {
        return $this->productRepository->updateStock($id, $quantity);
    }

    private function handleImages(Product $product, array $images): void
    {
        $uploadedImages = $this->imageService->uploadMultiple($images, ImageUploadService::TYPE_PRODUCTS, $product->slug);

        foreach ($uploadedImages as $index => $image) {
            $product->images()->attach($image->id, [
                'is_primary' => $index === 0,
            ]);
        }
    }

    public function copyProduct(Product $product): ?Product
    {
        return DB::transaction(function () use ($product) {
            // Replicate product fields (excluding id, timestamps, etc.)
            $newProduct = $product->replicate([
                'name', 'code', 'short_desc', 'description', 'slug', 'type',
                'regular_price', 'special_price', 'category_id', 'tags', 'featured',
                'availability', 'review', 'status', 'meta_description', 'meta_keywords'
            ]);
            $newProduct->save();

            // Copy details if type == 2
            if ($product->type == 2 && $product->details) {
                foreach ($product->details as $detail) {
                    $newDetail = $detail->replicate(['size', 'regular_price', 'special_price']);
                    $newDetail->product_id = $newProduct->id;
                    $newDetail->save();
                }
            }

            return $newProduct;
        });
    }

    public function all()
    {
        return $this->productRepository->all()->load('image');
    }
}
