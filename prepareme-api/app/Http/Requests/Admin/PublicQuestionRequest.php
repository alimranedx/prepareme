<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class PublicQuestionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    public function rules(): array
    {
        return [
            'subject_id' => ['required', 'exists:subjects,id'],
            'topic_id' => ['nullable', 'exists:topics,id'],
            'study_guide_id' => ['nullable', 'exists:study_guides,id'],
            'question' => ['required', 'string'],
            'answer' => ['required', 'string'],
            'explanation' => ['nullable', 'string'],
            'question_type' => ['required', 'in:short_answer,mcq,true_false'],
            'options' => ['nullable', 'array'],
            'correct_option' => ['nullable', 'string', 'max:50'],
            'difficulty' => ['nullable', 'in:easy,medium,hard'],
            'status' => ['nullable', 'in:draft,published,archived'],
        ];
    }
}
