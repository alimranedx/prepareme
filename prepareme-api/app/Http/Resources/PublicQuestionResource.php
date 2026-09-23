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
            'chapter_id' => $this->chapter_id,
            'topic_id' => $this->topic_id,
            'study_guide_id' => $this->study_guide_id,
            'source_id' => $this->source_id,
            'question' => $this->question,
            'answer' => $this->answer,
            'explanation' => $this->explanation,
            'question_type' => $this->question_type?->value ?? (string) $this->question_type,
            'options' => $this->options,
            'correct_option' => $this->correct_option,
            'difficulty' => $this->difficulty?->value ?? (string) $this->difficulty,
            'marks' => (float) ($this->marks ?? 1.00),
            'negative_marks' => (float) ($this->negative_marks ?? 0.50),
            'status' => $this->status?->value ?? (string) $this->status,
            'previous_exam_tag' => $this->source?->name ?? ($this->exams->first()?->name ?? null),
            'subject' => new SubjectResource($this->whenLoaded('subject')),
            'chapter' => new ChapterResource($this->whenLoaded('chapter')),
            'topic' => new TopicResource($this->whenLoaded('topic')),
            'source' => new QuestionSourceResource($this->whenLoaded('source')),
            'exams' => ExamResource::collection($this->whenLoaded('exams')),
            'options_list' => $this->whenLoaded('optionsList', function () {
                return $this->optionsList->map(function ($opt) {
                    return [
                        'id' => $opt->id,
                        'key' => $opt->option_key,
                        'text' => $opt->option_text,
                        'is_correct' => (bool) $opt->is_correct,
                    ];
                });
            }),
            'created_at' => $this->created_at?->toISOString(),
        ];
    }
}
