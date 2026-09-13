<?php

namespace App\Http\Requests\OCR;

use Illuminate\Foundation\Http\FormRequest;

class OcrUploadRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'image' => [
                'required',
                'file',
                'image',
                'mimes:jpeg,png,jpg,webp,tiff',
                'max:10240', // 10MB
            ],
            'language' => ['nullable', 'in:ben,eng,ben+eng'],
        ];
    }
}
