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
        'chapter_id',
        'topic_id',
        'study_guide_id',
        'source_id',
        'question',
        'answer',
        'explanation',
        'question_type',
        'options',
        'correct_option',
        'marks',
        'negative_marks',
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
            'marks' => 'decimal:2',
            'negative_marks' => 'decimal:2',
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

    public function chapter(): BelongsTo
    {
        return $this->belongsTo(Chapter::class);
    }

    public function topic(): BelongsTo
    {
        return $this->belongsTo(Topic::class);
    }

    public function studyGuide(): BelongsTo
    {
        return $this->belongsTo(StudyGuide::class);
    }

    public function source(): BelongsTo
    {
        return $this->belongsTo(QuestionSource::class, 'source_id');
    }

    public function optionsList(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(QuestionOption::class, 'public_question_id')->orderBy('sort_order', 'asc');
    }

    public function exams(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Exam::class, 'question_exams');
    }

    public function modelTests(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(ModelTest::class, 'model_test_questions');
    }

    public function testAnswers(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(TestAnswer::class, 'public_question_id');
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
