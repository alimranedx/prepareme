<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Enums\ContentStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\TopicRequest;
use App\Http\Resources\TopicResource;
use App\Models\Topic;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class AdminTopicController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Topic::with(['subject', 'parent'])
            ->withCount(['children', 'studyGuides', 'publicQuestions']);

        if ($request->filled('subject_id')) {
            $query->where('subject_id', $request->subject_id);
        }

        if ($request->filled('parent_id')) {
            $query->where('parent_id', $request->parent_id);
        }

        $topics = $query->ordered()->paginate(25);

        return TopicResource::collection($topics)->response();
    }

    public function store(TopicRequest $request): JsonResponse
    {
        $topic = Topic::create([
            'subject_id' => $request->subject_id,
            'parent_id' => $request->parent_id,
            'name' => $request->name,
            'slug' => $request->slug ? Str::slug($request->slug) : Str::slug($request->name),
            'description' => $request->description,
            'status' => $request->status ? ContentStatus::from($request->status) : ContentStatus::PUBLISHED,
            'sort_order' => $request->sort_order ?? 0,
            'created_by' => $request->user()->id,
            'updated_by' => $request->user()->id,
        ]);

        $topic->load(['subject', 'parent']);

        return response()->json([
            'message' => 'Topic created successfully.',
            'data' => new TopicResource($topic),
        ], Response::HTTP_CREATED);
    }

    public function update(TopicRequest $request, Topic $topic): JsonResponse
    {
        $topic->update([
            'subject_id' => $request->subject_id,
            'parent_id' => $request->parent_id,
            'name' => $request->name,
            'slug' => $request->slug ? Str::slug($request->slug) : $topic->slug,
            'description' => $request->description,
            'status' => $request->status ? ContentStatus::from($request->status) : $topic->status,
            'sort_order' => $request->sort_order ?? $topic->sort_order,
            'updated_by' => $request->user()->id,
        ]);

        $topic->load(['subject', 'parent']);

        return response()->json([
            'message' => 'Topic updated successfully.',
            'data' => new TopicResource($topic),
        ]);
    }

    public function destroy(Topic $topic): JsonResponse
    {
        $topic->delete();

        return response()->json([
            'message' => 'Topic deleted successfully.',
        ]);
    }
}
