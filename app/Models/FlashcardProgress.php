<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FlashcardProgress extends Model
{
    protected $table = 'flashcard_progress';

    protected $fillable = [
        'user_id',
        'material_id',
        'card_key',
        'box',
        'review_count',
        'fail_count',
        'next_review_at',
        'last_reviewed_at',
    ];

    protected $casts = [
        'box'              => 'integer',
        'review_count'     => 'integer',
        'fail_count'       => 'integer',
        'next_review_at'   => 'datetime',
        'last_reviewed_at' => 'datetime',
    ];

    /**
     * Jadwal interval Spaced Repetition (dalam hari) berdasarkan Leitner Box.
     */
    public const BOX_INTERVALS = [
        1 => 1,   // Box 1: Ulangi besok (1 hari)
        2 => 3,   // Box 2: Ulangi dalam 3 hari
        3 => 7,   // Box 3: Ulangi dalam 7 hari
        4 => 14,  // Box 4: Ulangi dalam 14 hari
        5 => 30,  // Box 5: Mastered! Ulangi dalam 30 hari
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function material(): BelongsTo
    {
        return $this->belongsTo(Material::class);
    }

    /**
     * Cek apakah kartu sudah masuk status dikuasai / hafal (Box >= 3).
     */
    public function isMastered(): bool
    {
        return $this->box >= 3;
    }

    /**
     * Cek apakah kartu sudah jatuh tempo untuk di-review.
     */
    public function isDue(): bool
    {
        return $this->next_review_at <= Carbon::now();
    }
}

