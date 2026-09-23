<?php

namespace App\Http\Controllers\Api\V1\Study;

use App\Http\Controllers\Controller;
use App\Http\Resources\ExamResource;
use App\Models\Exam;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ExamController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Exam::published()->ordered()->withCount(['subjects', 'sources']);

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->boolean('featured')) {
            $query->where('is_featured', true);
        }

        $exams = $query->get();

        return response()->json([
            'data' => ExamResource::collection($exams),
        ]);
    }

    public function show(string $examIdentifier): JsonResponse
    {
        $exam = Exam::published()
            ->where(function ($q) use ($examIdentifier) {
                if (is_numeric($examIdentifier)) {
                    $q->where('id', $examIdentifier);
                } else {
                    $q->where('slug', $examIdentifier);
                }
            })
            ->with(['subjects' => function ($q) {
                $q->ordered()->withCount(['chapters', 'publicQuestions']);
            }, 'sources' => function ($q) {
                $q->latest('year')->withCount('questions');
            }])
            ->firstOrFail();

        return response()->json([
            'data' => new ExamResource($exam),
        ]);
    }

    public function syllabus(string $examIdentifier): JsonResponse
    {
        $exam = Exam::published()
            ->where(function ($q) use ($examIdentifier) {
                if (is_numeric($examIdentifier)) {
                    $q->where('id', $examIdentifier);
                } else {
                    $q->where('slug', $examIdentifier);
                }
            })
            ->with(['subjects.chapters.publishedTopics' => function ($q) {
                $q->ordered()->withCount(['studyGuides', 'publicQuestions']);
            }])
            ->firstOrFail();

        $syllabusData = $exam->subjects->map(function ($subject) {
            return [
                'id' => $subject->id,
                'name' => $subject->name,
                'slug' => $subject->slug,
                'allocated_marks' => (float) ($subject->pivot->marks ?? 0),
                'chapters' => $subject->chapters->map(function ($chapter) {
                    return [
                        'id' => $chapter->id,
                        'name' => $chapter->name,
                        'slug' => $chapter->slug,
                        'topics' => $chapter->publishedTopics->map(function ($topic) {
                            return [
                                'id' => $topic->id,
                                'name' => $topic->name,
                                'slug' => $topic->slug,
                                'is_high_yield' => (bool) $topic->is_high_yield,
                                'study_guides_count' => $topic->study_guides_count,
                                'questions_count' => $topic->public_questions_count,
                            ];
                        }),
                    ];
                }),
            ];
        });

        return response()->json([
            'exam' => new ExamResource($exam),
            'syllabus' => $syllabusData,
        ]);
    }
}
