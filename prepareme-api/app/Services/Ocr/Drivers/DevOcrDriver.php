<?php

namespace App\Services\Ocr\Drivers;

use App\Services\Ocr\Contracts\OcrEngineInterface;
use App\Services\Ocr\OcrResultDto;

class DevOcrDriver implements OcrEngineInterface
{
    public function extractText(string $absolutePath, string $language = 'ben+eng'): OcrResultDto
    {
        if (! file_exists($absolutePath)) {
            throw new \RuntimeException("Image file does not exist at: {$absolutePath}");
        }

        // For dev testing, check if companion text file or recognizable fixture exists
        $info = pathinfo($absolutePath);
        $companionTxt = ($info['dirname'] ?? '') . DIRECTORY_SEPARATOR . ($info['filename'] ?? '') . '.txt';

        if (file_exists($companionTxt)) {
            $extracted = file_get_contents($companionTxt);
        } else {
            // Realistic job preparation text extract sample (Bangla & English)
            $extracted = match($language) {
                'ben' => "প্রশ্ন: 'চর্যাপদ' এর আদি কবি কে?\nউত্তর: লুইপা।\nব্যাখ্যা: হরপ্রসাদ শাস্ত্রী ১৯০৭ সালে নেপালের রাজদরবারের রয়েল লাইব্রেরি থেকে চর্যাপদের পুথি আবিষ্কার করেন। এতে মোট ৪৬.৫ টি পদ পাওয়া যায়।",
                'eng' => "Question: What is the antonym of 'Vague'?\nAnswer: Definite / Clear.\nExplanation: 'Vague' means uncertain, indefinite, or unclear character or meaning. 'Definite' means clearly stated or decided.",
                default => "প্রশ্ন: বাংলাদেশের প্রথম নারী প্রধানমন্ত্রী কে এবং তিনি কত সালে দায়িত্ব গ্রহণ করেন?\nউত্তর: বেগম খালেদা জিয়া, ১৯৯১ সালে।\nQuestion: Who is the author of 'Animal Farm'?\nAnswer: George Orwell.\nব্যাখ্যা: এটি একটি রাজনৈতিক ব্যঙ্গাত্মক উপন্যাস (Political Satire) যা ১৯৪৫ সালে প্রকাশিত হয়।\n\n[Dev OCR Adapter: Scanned page extracted successfully. Edit text before saving to your Notebook.]",
            };
        }

        return new OcrResultDto(
            rawText: $extracted,
            provider: 'dev-fallback',
            confidence: 94.50,
            reference: 'dev-local-' . uniqid()
        );
    }
}
