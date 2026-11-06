<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DailyActivity extends Model
{
    protected $fillable = [
        'user_id', 'activity_date', 'xp_earned', 'actions',
    ];

    protected $casts = [
        'activity_date' => 'date',
        'xp_earned'     => 'integer',
        'actions'       => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

