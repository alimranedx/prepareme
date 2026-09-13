<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PublicQuestionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'subject_id' => $this->subject_id,
            'topic_id' => $this->topic_id,
            'study_guide_id' => $this->study_guide_id,
            'question' => $this->question,
            'answer' => $this->answer,
            'explanation' => $this->explanation,
            'question_type' => $this->question_type?->value ?? (string) $this->question_type,
            'options' => $this->options,
            'correct_option' => $this->correct_option,
            'difficulty' => $this->difficulty?->value ?? (string) $this->difficulty,
            'status' => $this->status?->value ?? (string) $this->status,
            'subject' => new SubjectResource($this->whenLoaded('subject')),
            'topic' => new TopicResource($this->whenLoaded('topic')),
            'created_at' => $this->created_at?->toISOString(),
        ];
    }
}
