<?php

namespace App\Http\Controllers\Api\V1\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\PersonalQuestionRequest;
use App\Http\Resources\PersonalQuestionResource;
use App\Models\PersonalQuestion;
use App\Models\Tag;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class PersonalQuestionController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        $query = $user->personalQuestions()
            ->with(['subject', 'topic', 'tags']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        } else {
            $query->where('status', 'active');
        }

        if ($request->filled('subject_id')) {
            $query->where('subject_id', $request->subject_id);
        }

        if ($request->filled('topic_id')) {
            $query->where('topic_id', $request->topic_id);
        }

        if ($request->filled('tag')) {
            $tag = $request->tag;
            $query->whereHas('tags', fn($q) => $q->where('slug', $tag)->orWhere('name', $tag));
        }

        if ($request->filled('search')) {
            $search = '%' . $request->search . '%';
            $query->where(function ($q) use ($search) {
                $q->where('question', 'like', $search)
                  ->orWhere('answer', 'like', $search)
                  ->orWhere('explanation', 'like', $search)
                  ->orWhere('source_title', 'like', $search);
            });
        }

        $perPage = min((int) $request->input('per_page', 15), 50);
        $questions = $query->latest('id')->paginate($perPage);

        return PersonalQuestionResource::collection($questions)->response();
    }

    public function store(PersonalQuestionRequest $request): JsonResponse
    {
        $user = $request->user();

        $question = $user->personalQuestions()->create([
            'subject_id' => $request->subject_id,
            'topic_id' => $request->topic_id,
            'ocr_document_id' => $request->ocr_document_id,
            'question' => $request->question,
            'answer' => $request->answer,
            'explanation' => $request->explanation,
            'source_title' => $request->source_title,
            'source_page' => $request->source_page,
            'status' => $request->status ?? 'active',
        ]);

        if ($request->has('tags')) {
            $this->syncTags($user, $question, $request->tags);
        }

        $question->load(['subject', 'topic', 'tags']);

        return response()->json([
            'message' => 'Question added to your personal notebook.',
            'data' => new PersonalQuestionResource($question),
        ], Response::HTTP_CREATED);
    }

    public function show(Request $request, PersonalQuestion $question): JsonResponse
    {
        $this->authorize('view', $question);

        $question->load(['subject', 'topic', 'tags']);

        return response()->json([
            'data' => new PersonalQuestionResource($question),
        ]);
    }

    public function update(PersonalQuestionRequest $request, PersonalQuestion $question): JsonResponse
    {
        $this->authorize('update', $question);

        $question->update([
            'subject_id' => $request->subject_id,
            'topic_id' => $request->topic_id,
            'question' => $request->question,
            'answer' => $request->answer,
            'explanation' => $request->explanation,
            'source_title' => $request->source_title,
            'source_page' => $request->source_page,
            'status' => $request->status ?? $question->status,
        ]);

        if ($request->has('tags')) {
            $this->syncTags($request->user(), $question, $request->tags);
        }

        $question->load(['subject', 'topic', 'tags']);

        return response()->json([
            'message' => 'Personal question updated successfully.',
            'data' => new PersonalQuestionResource($question),
        ]);
    }

    public function destroy(Request $request, PersonalQuestion $question): JsonResponse
    {
        $this->authorize('delete', $question);

        $question->delete();

        return response()->json([
            'message' => 'Personal question removed from notebook.',
        ]);
    }

    public function archive(Request $request, PersonalQuestion $question): JsonResponse
    {
        $this->authorize('update', $question);

        $newStatus = $question->status === 'archived' ? 'active' : 'archived';
        $question->update(['status' => $newStatus]);

        return response()->json([
            'message' => "Question {$newStatus} successfully.",
            'data' => new PersonalQuestionResource($question),
        ]);
    }

    protected function syncTags($user, PersonalQuestion $question, array $tagNames): void
    {
        $tagIds = [];
        foreach ($tagNames as $name) {
            $trimmed = trim($name);
            if (empty($trimmed)) continue;

            $slug = Str::slug($trimmed);
            $tag = Tag::firstOrCreate(
                ['user_id' => $user->id, 'slug' => $slug],
                ['name' => $trimmed]
            );
            $tagIds[] = $tag->id;
        }

        $question->tags()->sync($tagIds);
    }
}
