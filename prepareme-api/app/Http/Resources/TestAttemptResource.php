<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TestAttemptResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'model_test_id' => $this->model_test_id,
            'user_id' => $this->user_id,
            'total_questions' => $this->total_questions,
            'total_answered' => $this->total_answered,
            'total_correct' => $this->total_correct,
            'total_wrong' => $this->total_wrong,
            'total_skipped' => $this->total_skipped,
            'score' => (float) $this->score,
            'percentage' => (float) ($this->percentage ?? 0),
            'accuracy_percentage' => (float) ($this->percentage ?? 0),
            'is_passed' => (bool) $this->is_passed,
            'status' => $this->status,
            'duration_seconds' => $this->duration_seconds,
            'started_at' => $this->started_at?->toISOString(),
            'submitted_at' => $this->submitted_at?->toISOString(),
            'model_test' => new ModelTestResource($this->whenLoaded('modelTest')),
            'answers' => $this->whenLoaded('answers', function () {
                return $this->answers->map(function ($ans) {
                    return [
                        'id' => $ans->id,
                        'question_id' => $ans->public_question_id,
                        'selected_option' => $ans->selected_option,
                        'is_correct' => (bool) $ans->is_correct,
                        'marks_awarded' => (float) $ans->marks_awarded,
                        'time_spent_seconds' => $ans->time_spent_seconds,
                        'question' => new PublicQuestionResource($ans->question),
                    ];
                });
            }),
            'created_at' => $this->created_at?->toISOString(),
        ];
    }
}
