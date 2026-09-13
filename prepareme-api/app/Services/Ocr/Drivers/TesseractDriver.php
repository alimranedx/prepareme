<?php

namespace App\Services\Ocr\Drivers;

use App\Services\Ocr\Contracts\OcrEngineInterface;
use App\Services\Ocr\OcrResultDto;
use Symfony\Component\Process\Process;

class TesseractDriver implements OcrEngineInterface
{
    public function __construct(
        protected ?string $binaryPath = null
    ) {
        $this->binaryPath = $binaryPath ?: env('OCR_TESSERACT_PATH', 'tesseract');
    }

    public function extractText(string $absolutePath, string $language = 'ben+eng'): OcrResultDto
    {
        if (! file_exists($absolutePath)) {
            throw new \RuntimeException("File not found at {$absolutePath}");
        }

        $cmdLang = match($language) {
            'ben' => 'ben',
            'eng' => 'eng',
            default => 'ben+eng',
        };

        $process = new Process([
            $this->binaryPath,
            $absolutePath,
            'stdout',
            '-l',
            $cmdLang,
        ]);

        $process->setTimeout(60);
        $process->run();

        if (! $process->isSuccessful()) {
            throw new \RuntimeException('Tesseract OCR execution failed: ' . $process->getErrorOutput());
        }

        $output = trim($process->getOutput());

        return new OcrResultDto(
            rawText: $output,
            provider: 'tesseract',
            confidence: 90.0,
            reference: 'tesseract-' . md5_file($absolutePath)
        );
    }
}
