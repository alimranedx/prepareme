<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Enums\ContentStatus;
use App\Enums\DifficultyLevel;
use App\Enums\QuestionType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PublicQuestionRequest;
use App\Http\Resources\PublicQuestionResource;
use App\Models\PublicQuestion;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminPublicQuestionController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = PublicQuestion::with(['subject', 'topic', 'studyGuide']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('subject_id')) {
            $query->where('subject_id', $request->subject_id);
        }

        if ($request->filled('topic_id')) {
            $query->where('topic_id', $request->topic_id);
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

        $questions = $query->latest('id')->paginate(20);

        return PublicQuestionResource::collection($questions)->response();
    }

    public function store(PublicQuestionRequest $request): JsonResponse
    {
        $question = PublicQuestion::create([
            'subject_id' => $request->subject_id,
            'topic_id' => $request->topic_id,
            'study_guide_id' => $request->study_guide_id,
            'question' => $request->question,
            'answer' => $request->answer,
            'explanation' => $request->explanation,
            'question_type' => QuestionType::from($request->question_type),
            'options' => $request->options,
            'correct_option' => $request->correct_option,
            'difficulty' => $request->difficulty ? DifficultyLevel::from($request->difficulty) : DifficultyLevel::MEDIUM,
            'status' => $request->status ? ContentStatus::from($request->status) : ContentStatus::PUBLISHED,
            'created_by' => $request->user()->id,
            'updated_by' => $request->user()->id,
        ]);

        $question->load(['subject', 'topic', 'studyGuide']);

        return response()->json([
            'message' => 'Public question created successfully.',
            'data' => new PublicQuestionResource($question),
        ], Response::HTTP_CREATED);
    }

    public function show(PublicQuestion $question): JsonResponse
    {
        $question->load(['subject', 'topic', 'studyGuide']);

        return response()->json([
            'data' => new PublicQuestionResource($question),
        ]);
    }

    public function update(PublicQuestionRequest $request, PublicQuestion $question): JsonResponse
    {
        $question->update([
            'subject_id' => $request->subject_id,
            'topic_id' => $request->topic_id,
            'study_guide_id' => $request->study_guide_id,
            'question' => $request->question,
            'answer' => $request->answer,
            'explanation' => $request->explanation,
            'question_type' => QuestionType::from($request->question_type),
            'options' => $request->options,
            'correct_option' => $request->correct_option,
            'difficulty' => $request->difficulty ? DifficultyLevel::from($request->difficulty) : $question->difficulty,
            'status' => $request->status ? ContentStatus::from($request->status) : $question->status,
            'updated_by' => $request->user()->id,
        ]);

        $question->load(['subject', 'topic', 'studyGuide']);

        return response()->json([
            'message' => 'Public question updated successfully.',
            'data' => new PublicQuestionResource($question),
        ]);
    }

    public function destroy(PublicQuestion $question): JsonResponse
    {
        $question->delete();

        return response()->json([
            'message' => 'Public question deleted successfully.',
        ]);
    }

    public function publish(PublicQuestion $question): JsonResponse
    {
        $question->update([
            'status' => ContentStatus::PUBLISHED,
        ]);

        return response()->json([
            'message' => 'Question published.',
            'data' => new PublicQuestionResource($question),
        ]);
    }

    public function archive(PublicQuestion $question): JsonResponse
    {
        $question->update([
            'status' => ContentStatus::ARCHIVED,
        ]);

        return response()->json([
            'message' => 'Question archived.',
            'data' => new PublicQuestionResource($question),
        ]);
    }
}
