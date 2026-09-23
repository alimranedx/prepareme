<?php

namespace App\Http\Controllers\Api\V1\Study;

use App\Http\Controllers\Controller;
use App\Http\Resources\ChapterResource;
use App\Http\Resources\TopicResource;
use App\Models\Chapter;
use App\Models\Subject;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ChapterController extends Controller
{
    public function index(Request $request, string $subjectIdentifier): JsonResponse
    {
        $subject = Subject::published()
            ->where(function ($q) use ($subjectIdentifier) {
                if (is_numeric($subjectIdentifier)) {
                    $q->where('id', $subjectIdentifier);
                } else {
                    $q->where('slug', $subjectIdentifier);
                }
            })
            ->firstOrFail();

        $chapters = $subject->chapters()
            ->published()
            ->ordered()
            ->withCount(['publishedRootTopics as topics_count'])
            ->with(['publishedRootTopics' => function ($q) {
                $q->ordered()->withCount(['studyGuides', 'publicQuestions']);
            }])
            ->get();

        return response()->json([
            'subject' => [
                'id' => $subject->id,
                'name' => $subject->name,
                'slug' => $subject->slug,
            ],
            'data' => ChapterResource::collection($chapters),
        ]);
    }

    public function show(string $chapterIdentifier): JsonResponse
    {
        $chapter = Chapter::published()
            ->where(function ($q) use ($chapterIdentifier) {
                if (is_numeric($chapterIdentifier)) {
                    $q->where('id', $chapterIdentifier);
                } else {
                    $q->where('slug', $chapterIdentifier);
                }
            })
            ->with(['subject', 'publishedTopics' => function ($q) {
                $q->ordered()->withCount(['studyGuides', 'publicQuestions']);
            }])
            ->firstOrFail();

        return response()->json([
            'data' => new ChapterResource($chapter),
        ]);
    }

    public function topics(string $chapterIdentifier): JsonResponse
    {
        $chapter = Chapter::published()
            ->where(function ($q) use ($chapterIdentifier) {
                if (is_numeric($chapterIdentifier)) {
                    $q->where('id', $chapterIdentifier);
                } else {
                    $q->where('slug', $chapterIdentifier);
                }
            })
            ->firstOrFail();

        $topics = $chapter->publishedTopics()
            ->ordered()
            ->with(['studyGuides' => fn($q) => $q->published()])
            ->withCount(['studyGuides' => fn($q) => $q->published(), 'publicQuestions' => fn($q) => $q->published()])
            ->get();

        return response()->json([
            'chapter' => new ChapterResource($chapter),
            'data' => TopicResource::collection($topics),
        ]);
    }
}
