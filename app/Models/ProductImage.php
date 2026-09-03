<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class ProductImage extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'product_id',
        'image',
        'is_primary'
    ];

    protected $casts = [
        'is_primary' => 'boolean'
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['product_id', 'image'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
