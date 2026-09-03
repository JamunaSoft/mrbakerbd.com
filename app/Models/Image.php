<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Image extends Model
{
    use HasFactory;

    protected $fillable = [
        'path',
        'name',
        'alt',
        'title',
        'width',
        'height',
        'size',
        'mime_type',
    ];

    protected $casts = [
        'width' => 'integer',
        'height' => 'integer',
        'size' => 'integer',
    ];

    public function galleryProducts(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_gallery')
            ->withTimestamps();
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'category_images')
            ->withTimestamps();
    }

    public function slides(): BelongsToMany
    {
        return $this->belongsToMany(Slide::class, 'slide_images')
            ->withTimestamps();
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_images')
            ->withTimestamps();
    }

    public function getUrlAttribute(): string
    {
        return asset($this->path . '/' . $this->name);
    }

    public function getThumbnailUrlAttribute(): string
    {
        // Extract product slug from path
        $parts = explode('/', $this->path);
        $productSlug = $parts[2] ?? ''; // Get the product slug part
        return asset('images/products/' . $productSlug . '/thumbs/' . $this->name);
    }
}
