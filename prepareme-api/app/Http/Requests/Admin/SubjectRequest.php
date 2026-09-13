<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SubjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    public function rules(): array
    {
        $subjectId = $this->route('subject')?->id ?? $this->route('subject');

        return [
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('subjects', 'slug')->ignore($subjectId)],
            'description' => ['nullable', 'string'],
            'status' => ['nullable', 'in:draft,published,archived'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ];
    }
}
