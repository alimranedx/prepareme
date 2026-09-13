<?php

namespace App\Http\Controllers\Api\V1\Study;

use App\Http\Controllers\Controller;
use App\Http\Resources\PublicQuestionResource;
use App\Models\PublicQuestion;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PublicQuestionController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = PublicQuestion::published()->with(['subject', 'topic']);

        if ($request->filled('subject_id')) {
            $query->where('subject_id', $request->subject_id);
        }

        if ($request->filled('topic_id')) {
            $query->where('topic_id', $request->topic_id);
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
            ->with(['subject', 'topic', 'studyGuide'])
            ->findOrFail($id);

        return response()->json([
            'data' => new PublicQuestionResource($question),
        ]);
    }
}
