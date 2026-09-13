<?php

namespace App\Jobs;

use App\Enums\OcrDocumentStatus;
use App\Models\OcrDocument;
use App\Models\OcrResult;
use App\Services\Ocr\OcrManager;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Throwable;

class ProcessOcrDocumentJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $timeout = 120;

    public function __construct(
        public OcrDocument $document
    ) {}

    public function handle(OcrManager $ocrManager): void
    {
        // Check if document still exists and status is pending/processing/uploaded
        $document = $this->document->fresh();
        if (! $document) {
            return;
        }

        $document->update([
            'status' => OcrDocumentStatus::PROCESSING,
            'error_message' => null,
        ]);

        try {
            $storagePath = Storage::disk('local')->path($document->original_file_path);

            if (! file_exists($storagePath)) {
                throw new \RuntimeException("Document file not found on disk.");
            }

            $driver = $ocrManager->driver();
            $resultDto = $driver->extractText($storagePath, $document->language->value);

            // Store OCR Result
            OcrResult::create([
                'ocr_document_id' => $document->id,
                'raw_text' => $resultDto->rawText,
                'corrected_text' => null,
                'provider' => $resultDto->provider,
                'provider_reference' => $resultDto->reference,
                'confidence' => $resultDto->confidence,
                'processed_at' => now(),
            ]);

            $document->update([
                'status' => OcrDocumentStatus::COMPLETED,
            ]);

        } catch (Throwable $e) {
            Log::error("OCR Processing Job Failed for Document ID {$document->id}: " . $e->getMessage());

            $document->update([
                'status' => OcrDocumentStatus::FAILED,
                'error_message' => 'OCR processing encountered an issue. Please retry or upload a clearer image.',
            ]);

            // If we still have tries left, rethrow to retry
            if ($this->attempts() < $this->tries) {
                throw $e;
            }
        }
    }

    public function failed(?Throwable $exception): void
    {
        $this->document->update([
            'status' => OcrDocumentStatus::FAILED,
            'error_message' => 'OCR processing failed after multiple attempts.',
        ]);
    }
}
