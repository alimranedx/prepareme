<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\OcrDocumentResource;
use App\Models\OcrDocument;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminOcrController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = OcrDocument::with(['user', 'latestResult']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('language')) {
            $query->where('language', $request->language);
        }

        $docs = $query->latest('id')->paginate(20);

        return OcrDocumentResource::collection($docs)->response();
    }

    public function show(OcrDocument $document): JsonResponse
    {
        $document->load(['user', 'results']);

        return response()->json([
            'data' => new OcrDocumentResource($document),
            'user' => [
                'id' => $document->user->id,
                'name' => $document->user->name,
                'email' => $document->user->email,
            ],
        ]);
    }
}
