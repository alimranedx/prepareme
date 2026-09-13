<?php

namespace App\Http\Controllers\Api\V1\Study;

use App\Http\Controllers\Controller;
use App\Http\Resources\TopicResource;
use App\Models\Topic;
use Illuminate\Http\JsonResponse;

class TopicController extends Controller
{
    public function show(string $topicIdentifier): JsonResponse
    {
        $topic = Topic::published()
            ->where(function ($q) use ($topicIdentifier) {
                if (is_numeric($topicIdentifier)) {
                    $q->where('id', $topicIdentifier);
                } else {
                    $q->where('slug', $topicIdentifier);
                }
            })
            ->with([
                'subject',
                'publishedChildren',
                'studyGuides' => fn($q) => $q->published()->orderBy('id', 'asc'),
            ])
            ->firstOrFail();

        return response()->json([
            'data' => new TopicResource($topic),
        ]);
    }
}
