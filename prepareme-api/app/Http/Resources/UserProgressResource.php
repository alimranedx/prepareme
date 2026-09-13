<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserProgressResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'study_guide_id' => $this->study_guide_id,
            'status' => $this->status?->value ?? (string) $this->status,
            'progress_percent' => $this->progress_percent,
            'last_read_at' => $this->last_read_at?->toISOString(),
            'completed_at' => $this->completed_at?->toISOString(),
            'study_guide' => new StudyGuideResource($this->whenLoaded('studyGuide')),
            'created_at' => $this->created_at?->toISOString(),
        ];
    }
}
