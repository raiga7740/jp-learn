<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserVocabulary extends Model
{
    use HasFactory;

    /**
     * Nama tabel di database.
     *
     * @var string
     */
    protected $table = 'user_vocabularies';

    /**
     * Atribut yang dapat diisi secara massal.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'word_key',
        'japanese',
        'reading',
        'romaji',
        'meaning',
        'category',
        'example_ja',
        'example_id',
        'notes',
        'is_favorite',
    ];

    /**
     * Konversi tipe data bawaan.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_favorite' => 'boolean',
    ];

    /**
     * Relasi ke model User pemilik kosakata ini.
     *
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

