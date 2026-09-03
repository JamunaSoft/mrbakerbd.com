<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductCustomization extends Model
{
    protected $fillable = [
        'product_id',
        'weight',
        'flavour',
        'special_request',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
