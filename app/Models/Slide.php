<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Slide extends Model
{
    use LogsActivity;

    protected $fillable = [
        'image',
        'text',
        'url',
        'new_window',
        'position',
        'status'
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['image', 'text', 'url', 'new_window', 'position', 'status'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
