<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TestAttempt extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'model_test_id',
        'started_at',
        'submitted_at',
        'duration_seconds',
        'total_questions',
        'total_answered',
        'total_correct',
        'total_wrong',
        'total_skipped',
        'score',
        'percentage',
        'is_passed',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'started_at' => 'datetime',
            'submitted_at' => 'datetime',
            'duration_seconds' => 'integer',
            'total_questions' => 'integer',
            'total_answered' => 'integer',
            'total_correct' => 'integer',
            'total_wrong' => 'integer',
            'total_skipped' => 'integer',
            'score' => 'decimal:2',
            'percentage' => 'decimal:2',
            'is_passed' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function modelTest(): BelongsTo
    {
        return $this->belongsTo(ModelTest::class);
    }

    public function answers(): HasMany
    {
        return $this->hasMany(TestAnswer::class, 'attempt_id');
    }
}
