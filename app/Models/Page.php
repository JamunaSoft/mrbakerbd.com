<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Page extends Model
{
    use LogsActivity;

    protected $fillable = [
        'title',
        'content',
        'position',
        'slug',
        'status',
        'meta_description',
        'meta_keywords'
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['title', 'content', 'position', 'slug', 'status', 'meta_description', 'meta_keywords'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
