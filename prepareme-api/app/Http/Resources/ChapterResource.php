<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ChapterResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'subject_id' => $this->subject_id,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'sort_order' => $this->sort_order,
            'status' => $this->status?->value ?? (string) $this->status,
            'topics_count' => $this->whenCounted('topics'),
            'topics' => TopicResource::collection($this->whenLoaded('topics')),
            'published_topics' => TopicResource::collection($this->whenLoaded('publishedTopics')),
            'subject' => new SubjectResource($this->whenLoaded('subject')),
            'created_at' => $this->created_at?->toISOString(),
        ];
    }
}
