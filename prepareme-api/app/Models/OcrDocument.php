<?php

namespace App\Models;

use App\Enums\OcrDocumentStatus;
use App\Enums\OcrLanguage;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class OcrDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'original_file_path',
        'original_file_name',
        'mime_type',
        'file_size',
        'language',
        'status',
        'error_message',
    ];

    protected function casts(): array
    {
        return [
            'status' => OcrDocumentStatus::class,
            'language' => OcrLanguage::class,
            'file_size' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function results(): HasMany
    {
        return $this->hasMany(OcrResult::class)->latest();
    }

    public function latestResult(): HasOne
    {
        return $this->hasOne(OcrResult::class)->latestOfMany();
    }

    public function personalQuestions(): HasMany
    {
        return $this->hasMany(PersonalQuestion::class);
    }
}
