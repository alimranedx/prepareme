<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OcrResultResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'ocr_document_id' => $this->ocr_document_id,
            'raw_text' => $this->raw_text,
            'corrected_text' => $this->corrected_text,
            'effective_text' => $this->effective_text,
            'provider' => $this->provider,
            'provider_reference' => $this->provider_reference,
            'confidence' => $this->confidence,
            'processed_at' => $this->processed_at?->toISOString(),
            'created_at' => $this->created_at?->toISOString(),
        ];
    }
}
