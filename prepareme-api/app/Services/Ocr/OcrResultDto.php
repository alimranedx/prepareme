<?php

namespace App\Services\Ocr;

class OcrResultDto
{
    public function __construct(
        public string $rawText,
        public ?string $provider = null,
        public ?float $confidence = null,
        public ?string $reference = null
    ) {}
}
