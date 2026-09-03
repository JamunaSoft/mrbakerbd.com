<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Customer extends Model
{
    use LogsActivity;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address'
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'email', 'phone', 'address'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
