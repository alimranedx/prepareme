<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class TopicRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    public function rules(): array
    {
        $topicId = $this->route('topic')?->id ?? $this->route('topic');

        return [
            'subject_id' => ['required', 'exists:subjects,id'],
            'parent_id' => [
                'nullable',
                'exists:topics,id',
                function ($attribute, $value, $fail) use ($topicId) {
                    if ($topicId && (int)$value === (int)$topicId) {
                        $fail('A topic cannot be its own parent.');
                    }
                },
            ],
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'status' => ['nullable', 'in:draft,published,archived'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ];
    }
}
