<?php

namespace App\Models;

use App\Enums\SectionType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudyGuideSection extends Model
{
    use HasFactory;

    protected $fillable = [
        'study_guide_id',
        'title',
        'content',
        'section_type',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'section_type' => SectionType::class,
            'sort_order' => 'integer',
        ];
    }

    public function studyGuide(): BelongsTo
    {
        return $this->belongsTo(StudyGuide::class);
    }
}
