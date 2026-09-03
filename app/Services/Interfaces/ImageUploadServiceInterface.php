<?php

namespace App\Services\Interfaces;

use App\Models\Image;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;

interface ImageUploadServiceInterface
{
    public function upload(UploadedFile $file, string $type, string $slug = null, bool $isGallery = false): Image;
    public function uploadMultiple(array $files, string $type, string $slug = null, bool $isGallery = false): Collection;
    public function delete(string $path): bool;
    public function getUrl(string $path, string $name): string;
    public function getThumbnailUrl(string $path, string $name): string;
}
