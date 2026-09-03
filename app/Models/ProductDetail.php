<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class ProductDetail extends Model
{
    use LogsActivity;

    protected $fillable = [
        'product_id',
        'size',
        'regular_price',
        'special_price'
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['product_id', 'size', 'regular_price', 'special_price'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
