<?php

namespace App\Http\Controllers\Api\V1\Study;

use App\Http\Controllers\Controller;
use App\Http\Resources\StudyGuideResource;
use App\Models\StudyGuide;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StudyGuideController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = StudyGuide::published()->with(['topic.subject']);

        if ($request->filled('topic_id')) {
            $query->where('topic_id', $request->topic_id);
        }

        if ($request->filled('subject_id')) {
            $query->whereHas('topic', fn($q) => $q->where('subject_id', $request->subject_id));
        }

        if ($request->filled('search')) {
            $search = '%' . $request->search . '%';
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', $search)
                  ->orWhere('summary', 'like', $search)
                  ->orWhere('content', 'like', $search);
            });
        }

        $perPage = min((int) $request->input('per_page', 12), 50);
        $guides = $query->latest('published_at')->paginate($perPage);

        return StudyGuideResource::collection($guides)->response();
    }

    public function show(Request $request, string $identifier): JsonResponse
    {
        $guide = StudyGuide::where(function ($q) use ($identifier) {
                if (is_numeric($identifier)) {
                    $q->where('id', $identifier);
                } else {
                    $q->where('slug', $identifier);
                }
            })
            ->with(['topic.subject', 'sections'])
            ->firstOrFail();

        $this->authorize('view', $guide);

        return response()->json([
            'data' => new StudyGuideResource($guide),
        ]);
    }
}
