<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TopicResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'subject_id' => $this->subject_id,
            'chapter_id' => $this->chapter_id,
            'parent_id' => $this->parent_id,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'is_high_yield' => (bool) $this->is_high_yield,
            'status' => $this->status?->value ?? (string) $this->status,
            'sort_order' => $this->sort_order,
            'children' => TopicResource::collection(
                $this->relationLoaded('publishedChildren')
                    ? $this->publishedChildren
                    : $this->whenLoaded('children')
            ),
            'study_guides' => StudyGuideResource::collection($this->whenLoaded('studyGuides')),
            'study_guides_count' => $this->whenCounted('studyGuides'),
            'public_questions_count' => $this->whenCounted('publicQuestions'),
            'created_at' => $this->created_at?->toISOString(),
        ];
    }
}
