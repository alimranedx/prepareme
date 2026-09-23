<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuestionSourceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'exam_id' => $this->exam_id,
            'name' => $this->name,
            'slug' => $this->slug,
            'year' => $this->year,
            'exam_date' => $this->exam_date?->format('Y-m-d'),
            'total_questions' => $this->total_questions,
            'questions_count' => $this->whenCounted('questions'),
            'exam' => new ExamResource($this->whenLoaded('exam')),
            'status' => $this->status?->value ?? (string) $this->status,
            'created_at' => $this->created_at?->toISOString(),
        ];
    }
}
