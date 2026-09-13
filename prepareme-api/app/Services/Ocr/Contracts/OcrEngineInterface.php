<?php

namespace App\Services\Ocr\Contracts;

use App\Services\Ocr\OcrResultDto;

interface OcrEngineInterface
{
    /**
     * Extract text from an image file located at $absolutePath.
     *
     * @param string $absolutePath
     * @param string $language 'ben', 'eng', or 'ben+eng'
     * @return OcrResultDto
     * @throws \Exception
     */
    public function extractText(string $absolutePath, string $language = 'ben+eng'): OcrResultDto;
}
