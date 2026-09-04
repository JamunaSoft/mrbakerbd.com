<?php

namespace App\Services;

use App\Models\Image;
use App\Services\Interfaces\ImageUploadServiceInterface;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Database\Eloquent\Collection;

class ImageUploadService implements ImageUploadServiceInterface
{
    private const THUMBNAIL_WIDTH = 300;
    private const THUMBNAIL_HEIGHT = 200;

    public const TYPE_PRODUCTS = 'products';
    public const TYPE_CATEGORIES = 'categories';
    public const TYPE_SLIDES = 'slides';
    public const TYPE_USERS = 'users';

    private $imageManager;

    public function __construct()
    {
        $this->imageManager = new ImageManager(new Driver());
    }

    public function upload(UploadedFile $file, string $type, ?string $slug = null, bool $isGallery = false): Image
    {
        // Get image dimensions and create Intervention image instance
        $dimensions = getimagesize($file->getRealPath());
        $image = $this->imageManager->read($file);

        // Generate unique filename
        $filename = uniqid() . '_' . $file->getClientOriginalName();

        // Build paths based on type and slug
        $paths = $this->buildPaths($type, $slug, $filename, $isGallery);

        // Create directories if they don't exist
        foreach ($paths as $path) {
            if ($path && !is_array($path)) {
                $directory = dirname($path);
                if (!file_exists($directory)) {
                    mkdir($directory, 0755, true);
                }
            }
        }

        // Save the main image
        $image->save($paths['main']);
        $image->toWebp(80)->save($paths['webp']);

        // Create and save thumbnail if needed
        if ($type === self::TYPE_PRODUCTS) {
            $image->resize(self::THUMBNAIL_WIDTH, self::THUMBNAIL_HEIGHT)
                ->save($paths['thumb']);
            $image->toWebp(80)->save($paths['thumbWebp']);
        }

        // Create and return the Image model
        return Image::create([
            'path' => $paths['relative'],
            'name' => $paths['filename'],
            'alt' => pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME),
            'title' => pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME),
            'width' => $dimensions[0],
            'height' => $dimensions[1],
            'size' => $file->getSize(),
            'mime_type' => $file->getMimeType(),
        ]);
    }

    public function uploadMultiple(array $files, string $type, ?string $slug = null, bool $isGallery = false): Collection
    {
        $images = new Collection();

        foreach ($files as $file) {
            if ($file instanceof UploadedFile) {
                $images->push($this->upload($file, $type, $slug, $isGallery));
            }
        }

        return $images;
    }

    public function delete(string $path): bool
    {
        $fullPath = public_path($path);
        $thumbnailPath = $this->getThumbnailPath($path);

        if (file_exists($fullPath) && is_file($fullPath)) {
            unlink($fullPath);
        }
        $webpPath = pathinfo($fullPath, PATHINFO_DIRNAME) . '/' . pathinfo($fullPath, PATHINFO_FILENAME) . '.webp';
        if (file_exists($webpPath) && is_file($webpPath)) {
            unlink($webpPath);
        }
        if (file_exists($thumbnailPath) && is_file($thumbnailPath)) {
            unlink($thumbnailPath);
        }
        $thumbnailWebpPath = public_path(pathinfo($path, PATHINFO_DIRNAME) . '/thumbs/' . pathinfo($path, PATHINFO_FILENAME) . '.webp');
        if (file_exists($thumbnailWebpPath) && is_file($thumbnailWebpPath)) {
            unlink($thumbnailWebpPath);
        }

        return true;
    }

    private function buildPaths(string $type, ?string $slug, string $filename, bool $isGallery = false): array
    {
        $basePath = 'images/' . $type;
        $relativePath = $basePath;

        switch ($type) {
            case self::TYPE_PRODUCTS:
                $relativePath .= '/' . $slug;
                if ($isGallery) {
                    $relativePath .= '/gallery';
                }
                $mainPath = public_path($relativePath . '/' . $filename);
                $thumbPath = public_path('images/' . $type . '/' . $slug . '/thumbs/' . $filename);
                break;
            case self::TYPE_CATEGORIES:
                if (!$slug) {
                    throw new \InvalidArgumentException("Slug is required for category image uploads");
                }
                $relativePath .= '/' . $slug;
                $mainPath = public_path($relativePath . '/' . $filename);
                $thumbPath = null;
                break;
            case self::TYPE_SLIDES:
                $mainPath = public_path($relativePath . '/' . $filename);
                $thumbPath = null;
                break;
            case self::TYPE_USERS:
                $mainPath = public_path($relativePath . '/' . $filename);
                $thumbPath = null;
                break;
            default:
                throw new \InvalidArgumentException("Invalid image type: {$type}");
        }

        // Create directories if they don't exist
        if ($mainPath) {
            $mainDir = dirname($mainPath);
            if (!file_exists($mainDir)) {
                mkdir($mainDir, 0755, true);
            }
        }
        if ($thumbPath) {
            $thumbDir = dirname($thumbPath);
            if (!file_exists($thumbDir)) {
                mkdir($thumbDir, 0755, true);
            }
        }

        return [
            'main' => $mainPath,
            'webp' => substr($mainPath, 0, -(strlen(pathinfo($mainPath, PATHINFO_EXTENSION)) + 1)) . '.webp',
            'thumb' => $thumbPath,
            'thumbWebp' => $thumbPath
                ? substr($thumbPath, 0, -(strlen(pathinfo($thumbPath, PATHINFO_EXTENSION)) + 1)) . '.webp'
                : null,
            'relative' => $relativePath,
            'filename' => $filename,
        ];
    }

    private function getThumbnailPath(string $path): string
    {
        $pathInfo = pathinfo($path);
        // Extract product slug from path
        $parts = explode('/', $pathInfo['dirname']);
        $productSlug = $parts[2] ?? ''; // Get the product slug part
        return 'images/products/' . $productSlug . '/thumbs/' . $pathInfo['basename'];
    }

    public function getUrl(string $path, string $name): string
    {
        return asset($path . '/' . $name);
    }

    public function getThumbnailUrl(string $path, string $name): string
    {
        // Extract product slug from path
        $parts = explode('/', $path);
        $productSlug = $parts[2] ?? ''; // Get the product slug part
        return asset('images/products/' . $productSlug . '/thumbs/' . $name);
    }
}
