<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OcrDocumentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'original_file_name' => $this->original_file_name,
            'mime_type' => $this->mime_type,
            'file_size' => $this->file_size,
            'language' => $this->language?->value ?? (string) $this->language,
            'status' => $this->status?->value ?? (string) $this->status,
            'error_message' => $this->error_message,
            'latest_result' => new OcrResultResource($this->whenLoaded('latestResult')),
            'results' => OcrResultResource::collection($this->whenLoaded('results')),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
