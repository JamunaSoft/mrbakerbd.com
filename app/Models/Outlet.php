<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Outlet extends Model
{
    protected $fillable = ['name', 'address', 'phone', 'status', 'sort_order'];

    protected $casts = ['status' => 'boolean', 'sort_order' => 'integer'];
}
