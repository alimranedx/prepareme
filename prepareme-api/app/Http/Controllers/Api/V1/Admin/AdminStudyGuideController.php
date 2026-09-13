<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Enums\ContentStatus;
use App\Enums\SectionType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StudyGuideRequest;
use App\Http\Resources\StudyGuideResource;
use App\Models\StudyGuide;
use App\Models\StudyGuideSection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class AdminStudyGuideController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = StudyGuide::with(['topic.subject', 'sections']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

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
                  ->orWhere('summary', 'like', $search);
            });
        }

        $guides = $query->latest('id')->paginate(15);

        return StudyGuideResource::collection($guides)->response();
    }

    public function store(StudyGuideRequest $request): JsonResponse
    {
        $guide = DB::transaction(function () use ($request) {
            $status = $request->status ? ContentStatus::from($request->status) : ContentStatus::DRAFT;
            $publishedAt = ($status === ContentStatus::PUBLISHED) ? ($request->published_at ?: now()) : null;

            $newGuide = StudyGuide::create([
                'topic_id' => $request->topic_id,
                'title' => $request->title,
                'slug' => $request->slug ? Str::slug($request->slug) : Str::slug($request->title) . '-' . Str::random(5),
                'summary' => $request->summary,
                'content' => $request->content,
                'status' => $status,
                'published_at' => $publishedAt,
                'created_by' => $request->user()->id,
                'updated_by' => $request->user()->id,
            ]);

            if ($request->has('sections')) {
                foreach ($request->sections as $index => $secData) {
                    $newGuide->sections()->create([
                        'title' => $secData['title'],
                        'content' => $secData['content'],
                        'section_type' => SectionType::from($secData['section_type'] ?? 'explanation'),
                        'sort_order' => $secData['sort_order'] ?? $index,
                    ]);
                }
            }

            return $newGuide;
        });

        $guide->load(['topic.subject', 'sections']);

        return response()->json([
            'message' => 'Study guide created successfully.',
            'data' => new StudyGuideResource($guide),
        ], Response::HTTP_CREATED);
    }

    public function show(StudyGuide $studyGuide): JsonResponse
    {
        $studyGuide->load(['topic.subject', 'sections']);

        return response()->json([
            'data' => new StudyGuideResource($studyGuide),
        ]);
    }

    public function update(StudyGuideRequest $request, StudyGuide $studyGuide): JsonResponse
    {
        DB::transaction(function () use ($request, $studyGuide) {
            $status = $request->status ? ContentStatus::from($request->status) : $studyGuide->status;
            $publishedAt = $studyGuide->published_at;
            if ($status === ContentStatus::PUBLISHED && ! $publishedAt) {
                $publishedAt = now();
            }

            $studyGuide->update([
                'topic_id' => $request->topic_id,
                'title' => $request->title,
                'slug' => $request->slug ? Str::slug($request->slug) : $studyGuide->slug,
                'summary' => $request->summary,
                'content' => $request->content,
                'status' => $status,
                'published_at' => $publishedAt,
                'updated_by' => $request->user()->id,
            ]);

            if ($request->has('sections')) {
                // Remove old sections and rebuild with provided ordered list
                $studyGuide->sections()->delete();
                foreach ($request->sections as $index => $secData) {
                    $studyGuide->sections()->create([
                        'title' => $secData['title'],
                        'content' => $secData['content'],
                        'section_type' => SectionType::from($secData['section_type'] ?? 'explanation'),
                        'sort_order' => $secData['sort_order'] ?? $index,
                    ]);
                }
            }
        });

        $studyGuide->load(['topic.subject', 'sections']);

        return response()->json([
            'message' => 'Study guide updated successfully.',
            'data' => new StudyGuideResource($studyGuide),
        ]);
    }

    public function destroy(StudyGuide $studyGuide): JsonResponse
    {
        $studyGuide->delete();

        return response()->json([
            'message' => 'Study guide deleted successfully.',
        ]);
    }

    public function publish(StudyGuide $studyGuide): JsonResponse
    {
        $studyGuide->update([
            'status' => ContentStatus::PUBLISHED,
            'published_at' => $studyGuide->published_at ?: now(),
        ]);

        return response()->json([
            'message' => 'Study guide published successfully.',
            'data' => new StudyGuideResource($studyGuide),
        ]);
    }

    public function archive(StudyGuide $studyGuide): JsonResponse
    {
        $studyGuide->update([
            'status' => ContentStatus::ARCHIVED,
        ]);

        return response()->json([
            'message' => 'Study guide archived.',
            'data' => new StudyGuideResource($studyGuide),
        ]);
    }
}
