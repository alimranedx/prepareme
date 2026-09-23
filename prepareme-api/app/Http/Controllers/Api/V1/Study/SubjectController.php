<?php

namespace App\Http\Controllers\Api\V1\Study;

use App\Http\Controllers\Controller;
use App\Http\Resources\SubjectResource;
use App\Http\Resources\TopicResource;
use App\Models\Subject;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $subjects = Subject::published()
            ->ordered()
            ->withCount(['topics', 'publicQuestions'])
            ->get();

        return response()->json([
            'data' => SubjectResource::collection($subjects),
        ]);
    }

    public function topics(string $subjectIdentifier): JsonResponse
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

        $topics = $subject->topics()
            ->roots()
            ->published()
            ->ordered()
            ->with([
                'chapter',
                'publishedChildren.studyGuides' => fn($q) => $q->published(),
                'studyGuides' => fn($q) => $q->published()
            ])
            ->withCount([
                'studyGuides' => fn($q) => $q->published(),
                'publicQuestions' => fn($q) => $q->published()
            ])
            ->get();

        return response()->json([
            'subject' => new SubjectResource($subject),
            'data' => TopicResource::collection($topics),
        ]);
    }
}
