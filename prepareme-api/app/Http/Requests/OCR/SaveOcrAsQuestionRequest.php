<?php

namespace App\Http\Requests\OCR;

use Illuminate\Foundation\Http\FormRequest;

class SaveOcrAsQuestionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'question' => ['required', 'string'],
            'answer' => ['required', 'string'],
            'explanation' => ['nullable', 'string'],
            'subject_id' => ['nullable', 'exists:subjects,id'],
            'topic_id' => ['nullable', 'exists:topics,id'],
            'source_title' => ['nullable', 'string', 'max:255'],
            'source_page' => ['nullable', 'string', 'max:50'],
            'corrected_text' => ['nullable', 'string'],
        ];
    }
}
