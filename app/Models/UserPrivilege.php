<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class UserPrivilege extends Model
{
    use LogsActivity;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'route_name',
    ];

    /**
     * Get the activity log options for the model.
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['user.name', 'route_name'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    /**
     * Get the user that owns the privilege.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
