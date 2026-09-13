<?php

namespace App\Http\Controllers\Api\V1\OCR;

use App\Enums\OcrDocumentStatus;
use App\Enums\OcrLanguage;
use App\Http\Controllers\Controller;
use App\Http\Requests\OCR\OcrUploadRequest;
use App\Http\Requests\OCR\SaveOcrAsQuestionRequest;
use App\Http\Resources\OcrDocumentResource;
use App\Http\Resources\OcrResultResource;
use App\Http\Resources\PersonalQuestionResource;
use App\Jobs\ProcessOcrDocumentJob;
use App\Models\OcrDocument;
use App\Models\OcrResult;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class OcrController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $documents = $request->user()->ocrDocuments()
            ->with(['latestResult'])
            ->latest('id')
            ->paginate(15);

        return OcrDocumentResource::collection($documents)->response();
    }

    public function upload(OcrUploadRequest $request): JsonResponse
    {
        $user = $request->user();
        $file = $request->file('image');

        // Generate randomized private filename
        $extension = $file->getClientOriginalExtension() ?: 'jpg';
        $randomFileName = Str::random(40) . '.' . $extension;
        $privatePath = $file->storeAs('private/ocr/' . $user->id, $randomFileName, 'local');

        $language = $request->input('language', 'ben+eng');

        $document = $user->ocrDocuments()->create([
            'original_file_path' => $privatePath,
            'original_file_name' => basename($file->getClientOriginalName()),
            'mime_type' => $file->getMimeType(),
            'file_size' => $file->getSize(),
            'language' => OcrLanguage::from($language),
            'status' => OcrDocumentStatus::UPLOADED,
        ]);

        // Dispatch background processing job
        ProcessOcrDocumentJob::dispatch($document);

        return response()->json([
            'message' => 'Image uploaded successfully. OCR processing initiated.',
            'data' => new OcrDocumentResource($document),
        ], Response::HTTP_CREATED);
    }

    public function show(Request $request, OcrDocument $document): JsonResponse
    {
        $this->authorize('view', $document);

        $document->load(['latestResult', 'results']);

        return response()->json([
            'data' => new OcrDocumentResource($document),
        ]);
    }

    public function retry(Request $request, OcrDocument $document): JsonResponse
    {
        $this->authorize('update', $document);

        $document->update([
            'status' => OcrDocumentStatus::UPLOADED,
            'error_message' => null,
        ]);

        ProcessOcrDocumentJob::dispatch($document);

        return response()->json([
            'message' => 'OCR processing job re-queued.',
            'data' => new OcrDocumentResource($document),
        ]);
    }

    public function convert(Request $request, OcrDocument $document): JsonResponse
    {
        return $this->retry($request, $document);
    }

    public function result(Request $request, OcrDocument $document): JsonResponse
    {
        $this->authorize('view', $document);

        $result = $document->latestResult;

        if (! $result) {
            return response()->json([
                'message' => 'OCR result is not ready yet or processing is in progress.',
                'status' => $document->status->value,
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'data' => new OcrResultResource($result),
        ]);
    }

    public function saveAsQuestion(SaveOcrAsQuestionRequest $request, OcrDocument $document): JsonResponse
    {
        $this->authorize('update', $document);

        $user = $request->user();

        // Update corrected text on result if user edited it
        if ($request->filled('corrected_text')) {
            $latestResult = $document->latestResult;
            if ($latestResult) {
                $latestResult->update([
                    'corrected_text' => $request->corrected_text,
                ]);
            }
        }

        // Save as Personal Question in the user's notebook
        $question = $user->personalQuestions()->create([
            'subject_id' => $request->subject_id,
            'topic_id' => $request->topic_id,
            'ocr_document_id' => $document->id,
            'question' => $request->question,
            'answer' => $request->answer,
            'explanation' => $request->explanation,
            'source_title' => $request->source_title ?: $document->original_file_name,
            'source_page' => $request->source_page,
            'status' => 'active',
        ]);

        $question->load(['subject', 'topic', 'tags']);

        return response()->json([
            'message' => 'Extracted OCR content saved to personal study notebook successfully.',
            'data' => new PersonalQuestionResource($question),
        ], Response::HTTP_CREATED);
    }

    public function destroy(Request $request, OcrDocument $document): JsonResponse
    {
        $this->authorize('delete', $document);

        // Remove physical private file
        if ($document->original_file_path && Storage::disk('local')->exists($document->original_file_path)) {
            Storage::disk('local')->delete($document->original_file_path);
        }

        $document->delete();

        return response()->json([
            'message' => 'OCR document and associated data deleted.',
        ]);
    }
}
