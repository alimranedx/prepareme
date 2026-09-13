<?php

namespace App\Http\Controllers\Api\V1\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\BookmarkResource;
use App\Models\Bookmark;
use App\Models\StudyGuide;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BookmarkController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $bookmarks = $request->user()->bookmarks()
            ->with(['studyGuide.topic.subject'])
            ->latest('id')
            ->paginate(20);

        return BookmarkResource::collection($bookmarks)->response();
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'study_guide_id' => 'required|exists:study_guides,id',
        ]);

        $guide = StudyGuide::published()->findOrFail($request->study_guide_id);

        $bookmark = Bookmark::firstOrCreate([
            'user_id' => $request->user()->id,
            'study_guide_id' => $guide->id,
        ]);

        $bookmark->load(['studyGuide.topic.subject']);

        return response()->json([
            'message' => 'Study guide added to bookmarks.',
            'data' => new BookmarkResource($bookmark),
        ], Response::HTTP_CREATED);
    }

    public function destroy(Request $request, int $studyGuideId): JsonResponse
    {
        $deleted = Bookmark::where('user_id', $request->user()->id)
            ->where('study_guide_id', $studyGuideId)
            ->delete();

        return response()->json([
            'message' => $deleted ? 'Bookmark removed.' : 'Bookmark was not found.',
        ]);
    }
}
