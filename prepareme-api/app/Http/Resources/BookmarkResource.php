<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookmarkResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'study_guide_id' => $this->study_guide_id,
            'study_guide' => new StudyGuideResource($this->whenLoaded('studyGuide')),
            'created_at' => $this->created_at?->toISOString(),
        ];
    }
}
