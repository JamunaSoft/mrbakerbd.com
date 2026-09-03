<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Category extends Model
{
    use LogsActivity;

    protected $fillable = [
        'name',
        'description',
        'icon_image_id',
        'banner_image_id',
        'position',
        'slug',
        'parent_id',
        'status',
        'meta_description',
        'meta_keywords',
        'is_customized',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'description', 'icon_image_id', 'banner_image_id', 'position', 'slug', 'parent_id', 'status', 'meta_description', 'meta_keywords'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function child(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function lr_products()
    {
        return $this->hasMany(Product::class)->where('status', 1)->limit(8)->inRandomOrder();
    }

    public function iconImage()
    {
        return $this->belongsTo(Image::class, 'icon_image_id');
    }

    public function bannerImage()
    {
        return $this->belongsTo(Image::class, 'banner_image_id');
    }
}
