<?php

namespace App\Http\Controllers\Api\V1\User;

use App\Enums\ProgressStatus;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserProgressResource;
use App\Models\StudyGuide;
use App\Models\UserProgress;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProgressController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $progress = $request->user()->progress()
            ->with(['studyGuide.topic.subject'])
            ->latest('last_read_at')
            ->paginate(20);

        return UserProgressResource::collection($progress)->response();
    }

    public function update(Request $request, int $studyGuideId): JsonResponse
    {
        $request->validate([
            'status' => 'nullable|in:not_started,in_progress,completed',
            'progress_percent' => 'nullable|integer|min:0|max:100',
        ]);

        $guide = StudyGuide::published()->findOrFail($studyGuideId);

        $progress = UserProgress::firstOrNew([
            'user_id' => $request->user()->id,
            'study_guide_id' => $guide->id,
        ]);

        if ($request->filled('progress_percent')) {
            $progress->progress_percent = (int) $request->progress_percent;
        }

        if ($request->filled('status')) {
            $progress->status = ProgressStatus::from($request->status);
        } else {
            if ($progress->progress_percent >= 100) {
                $progress->status = ProgressStatus::COMPLETED;
            } elseif ($progress->progress_percent > 0) {
                $progress->status = ProgressStatus::IN_PROGRESS;
            }
        }

        $progress->last_read_at = now();

        if ($progress->status === ProgressStatus::COMPLETED && ! $progress->completed_at) {
            $progress->completed_at = now();
        }

        $progress->save();
        $progress->load(['studyGuide.topic.subject']);

        return response()->json([
            'message' => 'Progress updated.',
            'data' => new UserProgressResource($progress),
        ]);
    }
}
