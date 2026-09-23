<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExamResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'category' => $this->category,
            'description' => $this->description,
            'total_marks' => (float) $this->total_marks,
            'duration_minutes' => $this->duration_minutes,
            'is_featured' => (bool) $this->is_featured,
            'sort_order' => $this->sort_order,
            'status' => $this->status?->value ?? (string) $this->status,
            'subjects_count' => $this->whenCounted('subjects'),
            'sources_count' => $this->whenCounted('sources'),
            'subjects' => $this->whenLoaded('subjects', function () {
                return $this->subjects->map(function ($subject) {
                    return [
                        'id' => $subject->id,
                        'name' => $subject->name,
                        'slug' => $subject->slug,
                        'marks' => (float) ($subject->pivot->marks ?? 0),
                        'sort_order' => $subject->pivot->sort_order ?? 0,
                    ];
                });
            }),
            'sources' => QuestionSourceResource::collection($this->whenLoaded('sources')),
            'created_at' => $this->created_at?->toISOString(),
        ];
    }
}
