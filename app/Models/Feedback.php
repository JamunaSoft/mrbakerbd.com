<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    protected $fillable = [
        'customer_name',
        'email',
        'phone',
        'reason',
        'feedback_message',
        'overall_rating',
    ];

    protected $casts = [
        'overall_rating' => 'integer',
    ];
}
