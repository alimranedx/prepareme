<?php

namespace App\Models;

use App\Enums\ContentStatus;
use App\Enums\DifficultyLevel;
use App\Enums\QuestionType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class PublicQuestion extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'subject_id',
        'topic_id',
        'study_guide_id',
        'question',
        'answer',
        'explanation',
        'question_type',
        'options',
        'correct_option',
        'difficulty',
        'status',
        'created_by',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'question_type' => QuestionType::class,
            'difficulty' => DifficultyLevel::class,
            'status' => ContentStatus::class,
            'options' => 'array',
        ];
    }

    public function scopePublished($query)
    {
        return $query->where('status', ContentStatus::PUBLISHED);
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function topic(): BelongsTo
    {
        return $this->belongsTo(Topic::class);
    }

    public function studyGuide(): BelongsTo
    {
        return $this->belongsTo(StudyGuide::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
