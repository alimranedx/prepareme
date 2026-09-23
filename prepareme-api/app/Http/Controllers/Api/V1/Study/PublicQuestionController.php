<?php

namespace App\Http\Controllers\Api\V1\Study;

use App\Http\Controllers\Controller;
use App\Http\Resources\PublicQuestionResource;
use App\Http\Resources\QuestionSourceResource;
use App\Models\PublicQuestion;
use App\Models\QuestionSource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PublicQuestionController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = PublicQuestion::published()->with(['subject', 'chapter', 'topic', 'source', 'exams', 'optionsList']);

        if ($request->filled('subject_id')) {
            $query->where('subject_id', $request->subject_id);
        }

        if ($request->filled('chapter_id')) {
            $query->where('chapter_id', $request->chapter_id);
        }

        if ($request->filled('topic_id')) {
            $query->where('topic_id', $request->topic_id);
        }

        if ($request->filled('source_id')) {
            $query->where('source_id', $request->source_id);
        }

        if ($request->filled('exam_id')) {
            $examId = $request->exam_id;
            $query->whereHas('exams', function ($q) use ($examId) {
                $q->where('exams.id', $examId);
            });
        }

        if ($request->filled('study_guide_id')) {
            $query->where('study_guide_id', $request->study_guide_id);
        }

        if ($request->filled('difficulty')) {
            $query->where('difficulty', $request->difficulty);
        }

        if ($request->filled('question_type')) {
            $query->where('question_type', $request->question_type);
        }

        if ($request->filled('search')) {
            $search = '%' . $request->search . '%';
            $query->where(function ($q) use ($search) {
                $q->where('question', 'like', $search)
                  ->orWhere('answer', 'like', $search)
                  ->orWhere('explanation', 'like', $search);
            });
        }

        $perPage = min((int) $request->input('per_page', 15), 50);
        $questions = $query->latest('id')->paginate($perPage);

        return PublicQuestionResource::collection($questions)->response();
    }

    public function show(int $id): JsonResponse
    {
        $question = PublicQuestion::published()
            ->with(['subject', 'chapter', 'topic', 'studyGuide', 'source', 'exams', 'optionsList'])
            ->findOrFail($id);

        return response()->json([
            'data' => new PublicQuestionResource($question),
        ]);
    }

    public function previousQuestions(Request $request): JsonResponse
    {
        $query = PublicQuestion::published()
            ->whereNotNull('source_id')
            ->with(['subject', 'chapter', 'topic', 'source.exam', 'exams', 'optionsList']);

        if ($request->filled('exam_id')) {
            $examId = $request->exam_id;
            $query->where(function ($q) use ($examId) {
                $q->whereHas('exams', fn($sub) => $sub->where('exams.id', $examId))
                  ->orWhereHas('source', fn($sub) => $sub->where('exam_id', $examId));
            });
        }

        if ($request->filled('source_id')) {
            $query->where('source_id', $request->source_id);
        }

        if ($request->filled('year')) {
            $year = $request->year;
            $query->whereHas('source', fn($q) => $q->where('year', $year));
        }

        if ($request->filled('subject_id')) {
            $query->where('subject_id', $request->subject_id);
        }

        if ($request->filled('chapter_id')) {
            $query->where('chapter_id', $request->chapter_id);
        }

        if ($request->filled('topic_id')) {
            $query->where('topic_id', $request->topic_id);
        }

        if ($request->filled('search')) {
            $search = '%' . $request->search . '%';
            $query->where(function ($q) use ($search) {
                $q->where('question', 'like', $search)
                  ->orWhere('answer', 'like', $search)
                  ->orWhere('explanation', 'like', $search);
            });
        }

        $perPage = min((int) $request->input('per_page', 20), 100);
        $questions = $query->latest('id')->paginate($perPage);

        return PublicQuestionResource::collection($questions)->response();
    }

    public function sources(Request $request): JsonResponse
    {
        $query = QuestionSource::published()->with('exam')->withCount('questions');

        if ($request->filled('exam_id')) {
            $query->where('exam_id', $request->exam_id);
        }

        if ($request->filled('year')) {
            $query->where('year', $request->year);
        }

        $sources = $query->latest('year')->get();

        return response()->json([
            'data' => QuestionSourceResource::collection($sources),
        ]);
    }

    public function showSource(string $sourceIdentifier): JsonResponse
    {
        $source = QuestionSource::published()
            ->where(function ($q) use ($sourceIdentifier) {
                if (is_numeric($sourceIdentifier)) {
                    $q->where('id', $sourceIdentifier);
                } else {
                    $q->where('slug', $sourceIdentifier);
                }
            })
            ->with(['exam', 'questions' => function ($q) {
                $q->published()->with(['subject', 'chapter', 'topic', 'optionsList']);
            }])
            ->firstOrFail();

        return response()->json([
            'data' => new QuestionSourceResource($source),
            'questions' => PublicQuestionResource::collection($source->questions),
        ]);
    }
}
