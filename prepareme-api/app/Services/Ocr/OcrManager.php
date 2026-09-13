<?php

namespace App\Services\Ocr;

use App\Services\Ocr\Contracts\OcrEngineInterface;
use App\Services\Ocr\Drivers\DevOcrDriver;
use App\Services\Ocr\Drivers\TesseractDriver;
use InvalidArgumentException;

class OcrManager
{
    protected array $drivers = [];

    public function driver(?string $name = null): OcrEngineInterface
    {
        $name = $name ?: config('services.ocr.driver', 'dev');

        if (! isset($this->drivers[$name])) {
            $this->drivers[$name] = $this->createDriver($name);
        }

        return $this->drivers[$name];
    }

    protected function createDriver(string $name): OcrEngineInterface
    {
        return match ($name) {
            'dev' => new DevOcrDriver(),
            'tesseract' => new TesseractDriver(config('services.ocr.tesseract_path')),
            default => new DevOcrDriver(),
        };
    }
}
