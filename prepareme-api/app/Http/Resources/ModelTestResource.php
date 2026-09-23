<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ModelTestResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'exam_id' => $this->exam_id,
            'subject_id' => $this->subject_id,
            'model_test_type' => $this->model_test_type,
            'description' => $this->description,
            'total_questions' => $this->total_questions,
            'total_marks' => (float) $this->total_marks,
            'pass_marks' => (float) $this->pass_marks,
            'duration_minutes' => $this->duration_minutes,
            'negative_marking_rate' => (float) $this->negative_marking_rate,
            'status' => $this->status?->value ?? (string) $this->status,
            'published_at' => $this->published_at?->toISOString(),
            'exam' => new ExamResource($this->whenLoaded('exam')),
            'subject' => new SubjectResource($this->whenLoaded('subject')),
            'questions_count' => $this->whenCounted('questions'),
            'questions' => PublicQuestionResource::collection($this->whenLoaded('questions')),
            'created_at' => $this->created_at?->toISOString(),
        ];
    }
}
