<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StudyGuideRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    public function rules(): array
    {
        $guideId = $this->route('studyGuide')?->id ?? $this->route('studyGuide') ?? $this->route('study_guide')?->id ?? $this->route('study_guide');

        return [
            'topic_id' => ['required', 'exists:topics,id'],
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('study_guides', 'slug')->ignore($guideId)],
            'summary' => ['nullable', 'string'],
            'content' => ['required', 'string'],
            'status' => ['nullable', 'in:draft,published,archived'],
            'published_at' => ['nullable', 'date'],
            'sections' => ['nullable', 'array'],
            'sections.*.id' => ['nullable', 'integer'],
            'sections.*.title' => ['required_with:sections', 'string', 'max:255'],
            'sections.*.content' => ['required_with:sections', 'string'],
            'sections.*.section_type' => ['required_with:sections', 'in:explanation,example,formula,summary,practice'],
            'sections.*.sort_order' => ['nullable', 'integer', 'min:0'],
        ];
    }
}
