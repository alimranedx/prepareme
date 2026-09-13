<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OcrResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'ocr_document_id',
        'raw_text',
        'corrected_text',
        'provider',
        'provider_reference',
        'confidence',
        'processed_at',
    ];

    protected function casts(): array
    {
        return [
            'confidence' => 'decimal:2',
            'processed_at' => 'datetime',
        ];
    }

    public function document(): BelongsTo
    {
        return $this->belongsTo(OcrDocument::class, 'ocr_document_id');
    }

    /**
     * Return user-editable or final text (corrected if available, otherwise raw).
     */
    public function getEffectiveTextAttribute(): string
    {
        return $this->corrected_text ?? $this->raw_text ?? '';
    }
}
