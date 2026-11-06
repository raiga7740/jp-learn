<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JlptExamResult extends Model
{
    use HasFactory;

    /**
     * Nama tabel di database.
     *
     * @var string
     */
    protected $table = 'jlpt_exam_results';

    /**
     * Atribut yang dapat diisi secara massal.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'certificate_no',
        'score',
        'score_vocabulary',
        'score_grammar',
        'score_reading',
        'total_questions',
        'correct_answers',
        'is_passed',
        'grade',
        'time_taken',
        'answers',
    ];

    /**
     * Konversi tipe data bawaan.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_passed'        => 'boolean',
        'answers'          => 'array',
        'score'            => 'integer',
        'score_vocabulary' => 'integer',
        'score_grammar'    => 'integer',
        'score_reading'    => 'integer',
        'total_questions'  => 'integer',
        'correct_answers'  => 'integer',
        'time_taken'       => 'integer',
    ];

    /**
     * Relasi ke User peserta ujian.
     *
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

