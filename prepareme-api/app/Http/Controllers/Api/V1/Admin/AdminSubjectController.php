<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Enums\ContentStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SubjectRequest;
use App\Http\Resources\SubjectResource;
use App\Models\Subject;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class AdminSubjectController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $subjects = Subject::ordered()
            ->withCount(['topics', 'publicQuestions'])
            ->paginate(20);

        return SubjectResource::collection($subjects)->response();
    }

    public function store(SubjectRequest $request): JsonResponse
    {
        $subject = Subject::create([
            'name' => $request->name,
            'slug' => $request->slug ? Str::slug($request->slug) : Str::slug($request->name),
            'description' => $request->description,
            'status' => $request->status ? ContentStatus::from($request->status) : ContentStatus::PUBLISHED,
            'sort_order' => $request->sort_order ?? 0,
            'created_by' => $request->user()->id,
            'updated_by' => $request->user()->id,
        ]);

        return response()->json([
            'message' => 'Subject created successfully.',
            'data' => new SubjectResource($subject),
        ], Response::HTTP_CREATED);
    }

    public function show(Subject $subject): JsonResponse
    {
        $subject->load(['topics']);

        return response()->json([
            'data' => new SubjectResource($subject),
        ]);
    }

    public function update(SubjectRequest $request, Subject $subject): JsonResponse
    {
        $subject->update([
            'name' => $request->name,
            'slug' => $request->slug ? Str::slug($request->slug) : $subject->slug,
            'description' => $request->description,
            'status' => $request->status ? ContentStatus::from($request->status) : $subject->status,
            'sort_order' => $request->sort_order ?? $subject->sort_order,
            'updated_by' => $request->user()->id,
        ]);

        return response()->json([
            'message' => 'Subject updated successfully.',
            'data' => new SubjectResource($subject),
        ]);
    }

    public function destroy(Subject $subject): JsonResponse
    {
        $subject->delete();

        return response()->json([
            'message' => 'Subject deleted successfully.',
        ]);
    }
}
