<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class PersonalQuestionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'subject_id' => ['nullable', 'exists:subjects,id'],
            'topic_id' => ['nullable', 'exists:topics,id'],
            'ocr_document_id' => ['nullable', 'exists:ocr_documents,id'],
            'question' => ['required', 'string'],
            'answer' => ['required', 'string'],
            'explanation' => ['nullable', 'string'],
            'source_title' => ['nullable', 'string', 'max:255'],
            'source_page' => ['nullable', 'string', 'max:50'],
            'status' => ['nullable', 'in:active,archived'],
            'tags' => ['nullable', 'array'],
            'tags.*' => ['string', 'max:50'],
        ];
    }
}
