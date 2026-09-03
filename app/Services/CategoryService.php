<?php

namespace App\Services;

use App\Models\Category;
use App\Repositories\Interfaces\CategoryRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;
use App\Repositories\Interfaces\RepositoryInterface;
use Illuminate\Support\Facades\Log;

class CategoryService extends BaseService
{
    protected RepositoryInterface|CategoryRepositoryInterface $repository;

    public function __construct(
        CategoryRepositoryInterface $repository,
        protected ImageUploadService $imageUploadService
    ) {
        parent::__construct($repository);
    }

    public function create(array $data): Category
    {
        $data['slug'] = Str::slug($data['name']);

        // Convert empty parent_id to null
        if (isset($data['parent_id']) && $data['parent_id'] === '') {
            $data['parent_id'] = null;
        }

        $data['is_customized'] = isset($data['is_customized']) ? 1 : 0;

        if (isset($data['icon'])) {
            $uploadedIcon = $this->imageUploadService->upload($data['icon'], ImageUploadService::TYPE_CATEGORIES, 'icons');
            $data['icon_image_id'] = $uploadedIcon->id;
            Log::debug('Icon uploaded', ['icon_image_id' => $uploadedIcon->id, 'icon_path' => $uploadedIcon->path]);
            unset($data['icon']);
        }

        if (isset($data['banner'])) {
            $uploadedBanner = $this->imageUploadService->upload($data['banner'], ImageUploadService::TYPE_CATEGORIES, 'banners');
            $data['banner_image_id'] = $uploadedBanner->id;
            Log::debug('Banner uploaded', ['banner_image_id' => $uploadedBanner->id, 'banner_path' => $uploadedBanner->path]);
            unset($data['banner']);
        }

        Log::debug('Category create data', $data);
        return $this->repository->create($data);
    }

    public function update($id, array $data): bool
    {
        if (isset($data['name'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        // Convert empty parent_id to null
        if (isset($data['parent_id']) && $data['parent_id'] === '') {
            $data['parent_id'] = null;
        }

        $data['is_customized'] = isset($data['is_customized']) ? 1 : 0;

        $category = $this->repository->find($id);

        if (isset($data['icon'])) {
            if ($category->iconImage) {
                $this->imageUploadService->delete($category->iconImage->path . '/' . $category->iconImage->name);
                $category->iconImage->delete();
            }
            $uploadedIcon = $this->imageUploadService->upload($data['icon'], ImageUploadService::TYPE_CATEGORIES, 'icons');
            $data['icon_image_id'] = $uploadedIcon->id;
            unset($data['icon']);
        }

        if (isset($data['banner'])) {
            if ($category->bannerImage) {
                $this->imageUploadService->delete($category->bannerImage->path . '/' . $category->bannerImage->name);
                $category->bannerImage->delete();
            }
            $uploadedBanner = $this->imageUploadService->upload($data['banner'], ImageUploadService::TYPE_CATEGORIES, 'banners');
            $data['banner_image_id'] = $uploadedBanner->id;
            unset($data['banner']);
        }

        return (bool) $this->repository->update($id, $data);
    }

    public function delete($id): bool
    {
        $category = $this->repository->find($id);

        if ($category->iconImage) {
            $this->imageUploadService->delete($category->iconImage->path . '/' . $category->iconImage->name);
        }

        if ($category->bannerImage) {
            $this->imageUploadService->delete($category->bannerImage->path . '/' . $category->bannerImage->name);
        }

        return $this->repository->delete($id);
    }

    public function getRootCategories(): Collection
    {
        return $this->repository->getRootCategories();
    }

    public function getChildren(int $parentId): Collection
    {
        return $this->repository->getChildren($parentId);
    }

    public function getWithProducts(): Collection
    {
        return $this->repository->getWithProducts();
    }

    public function findBySlug(string $slug): ?Category
    {
        return $this->repository->findBySlug($slug);
    }
}
