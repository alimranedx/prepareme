<?php

namespace App\Models;

use App\Enums\ProgressStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserProgress extends Model
{
    use HasFactory;

    protected $table = 'user_progress';

    protected $fillable = [
        'user_id',
        'study_guide_id',
        'status',
        'progress_percent',
        'last_read_at',
        'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'status' => ProgressStatus::class,
            'progress_percent' => 'integer',
            'last_read_at' => 'datetime',
            'completed_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function studyGuide(): BelongsTo
    {
        return $this->belongsTo(StudyGuide::class);
    }
}
