<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudyGuideResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $user = $request->user('sanctum');

        $isBookmarked = false;
        $progress = null;

        if ($user) {
            $isBookmarked = $this->bookmarks()->where('user_id', $user->id)->exists();
            $progressRecord = $this->userProgress()->where('user_id', $user->id)->first();
            if ($progressRecord) {
                $progress = [
                    'status' => $progressRecord->status?->value ?? (string) $progressRecord->status,
                    'progress_percent' => $progressRecord->progress_percent,
                    'last_read_at' => $progressRecord->last_read_at?->toISOString(),
                    'completed_at' => $progressRecord->completed_at?->toISOString(),
                ];
            }
        }

        return [
            'id' => $this->id,
            'topic_id' => $this->topic_id,
            'title' => $this->title,
            'slug' => $this->slug,
            'summary' => $this->summary,
            'content' => $this->content,
            'status' => $this->status?->value ?? (string) $this->status,
            'published_at' => $this->published_at?->toISOString(),
            'topic' => new TopicResource($this->whenLoaded('topic')),
            'sections' => StudyGuideSectionResource::collection($this->whenLoaded('sections')),
            'is_bookmarked' => $isBookmarked,
            'user_progress' => $progress,
            'created_at' => $this->created_at?->toISOString(),
        ];
    }
}
