<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Material extends Model
{
    protected $fillable = [
        'title', 'slug', 'category', 'level',
        'description', 'content', 'order', 'is_active',
    ];

    protected $casts = [
        'content'   => 'array',
        'is_active' => 'boolean',
    ];

    public function quizzes(): HasMany
    {
        return $this->hasMany(Quiz::class);
    }

    public function userProgress(): HasMany
    {
        return $this->hasMany(UserProgress::class);
    }

    public function getLevelColorAttribute(): string
    {
        return match ($this->level) {
            'beginner'     => '#1f5b45',
            'intermediate' => '#b3153b',
            'advanced'     => '#17143a',
            default        => '#4b4766',
        };
    }

    public function getCategoryIconAttribute(): string
    {
        return match ($this->category) {
            'hiragana'   => 'あ',
            'katakana'   => 'ア',
            'kanji'      => '漢',
            'vocabulary' => '語',
            'grammar'    => '文',
            default      => '学',
        };
    }
}
