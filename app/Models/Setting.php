<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    public function getGoogleTagManagerIdAttribute($value): string
    {
        return $value ?? '';
    }

    protected $fillable = [
        'key',
        'value'
    ];
}
