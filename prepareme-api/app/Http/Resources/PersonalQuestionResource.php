<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PersonalQuestionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'subject_id' => $this->subject_id,
            'topic_id' => $this->topic_id,
            'ocr_document_id' => $this->ocr_document_id,
            'question' => $this->question,
            'answer' => $this->answer,
            'explanation' => $this->explanation,
            'source_title' => $this->source_title,
            'source_page' => $this->source_page,
            'status' => $this->status,
            'subject' => new SubjectResource($this->whenLoaded('subject')),
            'topic' => new TopicResource($this->whenLoaded('topic')),
            'tags' => $this->tags->map(fn($t) => ['id' => $t->id, 'name' => $t->name, 'slug' => $t->slug]),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
