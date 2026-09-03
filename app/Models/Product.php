<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Product extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'sale_price',
        'sku',
        'stock',
        'status',
        'featured',
        'category_id',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'type',
        'code',
        'short_desc',
        'image_id',
        'regular_price',
        'special_price',
        'tags',
        'availability',
        'review',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'stock' => 'integer',
        'status' => 'boolean',
        'featured' => 'boolean',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'code', 'short_desc', 'description', 'image', 'slug', 'type', 'category_id', 'tags', 'featured', 'availability', 'review', 'status', 'meta_description', 'meta_keywords'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function image(): BelongsTo
    {
        return $this->belongsTo(Image::class);
    }

    public function details(): HasMany
    {
        return $this->hasMany(ProductDetail::class);
    }


    public function images(): BelongsToMany
    {
        return $this->belongsToMany(Image::class, 'product_gallery')
            ->withTimestamps();
    }

    public function getMainImageAttribute()
    {
        return $this->images()->first();
    }

    public function getFormattedPriceAttribute(): string
    {
        return number_format($this->price, 2);
    }

    public function getFormattedSalePriceAttribute(): ?string
    {
        return $this->sale_price ? number_format($this->sale_price, 2) : null;
    }

    public function getDiscountPercentageAttribute(): ?int
    {
        if (!$this->sale_price || $this->sale_price >= $this->price) {
            return null;
        }

        return (int) round((($this->price - $this->sale_price) / $this->price) * 100);
    }

    public function isInStock(): bool
    {
        return $this->stock > 0;
    }

    public function isOnSale(): bool
    {
        return $this->sale_price !== null && $this->sale_price < $this->price;
    }
}
